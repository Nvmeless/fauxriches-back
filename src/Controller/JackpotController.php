<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\PoolCompletion;
use App\Repository\PoolRepository;
use App\Repository\PlayerRepository;
use App\Repository\SongRepository;
use PhpParser\Node\Expr\Cast\Array_;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use App\Repository\PoolCompletionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JackpotController extends AbstractController
{
    #[Route('/jackpot/code', name: 'app_jackpot')]
    public function index(SerializerInterface $serializer, SongRepository $songRepository): JsonResponse
    {

        $data = $serializer->serialize($songRepository->findAll(), 'json', ['groups' => 'getSongs']);
        return new JsonResponse($data, 200, [], true);
    }


    #[Route('jackpot/{code}', name: 'jackpot.roll')]
    public function rollView(string $code, PoolCompletionRepository $poolCompletionRepository, EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PoolRepository $poolRepository, SerializerInterface $serializer, Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $result = $this->roll($code, $request, $poolCompletionRepository, $entityManager, $playerRepository, $poolRepository, $serializer, $urlGenerator);
        $pool = $result["pool"];
        $song = $result["song"];
        return $this->render('jackpot/jackpot.html.twig', [
            "pool" => $pool->getName(),
            "name" => $song->getName(),
            "link" => $song->getUrl()
        ]);
    }
    #[Route('api/jackpot/{code}', name: 'jackpot.roll.api')]
    // public function roll(string $code,PoolCompletionRepository $poolCompletionRepository, EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PoolRepository $poolRepository, SerializerInterface $serializer, Request $request, UrlGeneratorInterface $urlGenerator): JsonResponse
    public function rollApi(string $code, PoolCompletionRepository $poolCompletionRepository, EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PoolRepository $poolRepository, SerializerInterface $serializer, Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $result = $this->roll($code, $request, $poolCompletionRepository, $entityManager, $playerRepository, $poolRepository, $serializer, $urlGenerator);
        $pool = $result["pool"];
        $song = $result["song"];
        $jsonSongs = $serializer->serialize($song, 'json', ["groups" => "getSongs"]);
        return new JsonResponse($jsonSongs, Response::HTTP_OK, [], true);
    }

    private function roll(string $code, Request $request, PoolCompletionRepository $poolCompletionRepository, EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PoolRepository $poolRepository, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): array
    {
        $playerIp = $request->getClientIp();
        $player = $playerRepository->findBy(["ip" => $playerIp]);
        $pools = $poolRepository->findBy(["code" => $code]);
        if (count($pools)) {
            $pool = $pools[0];
            if (!count($player)) {
                $player = new Player();


                $player->setIp($playerIp);
                $entityManager->persist($player);
                $entityManager->flush();
                $hasDoneThisPool = [];
            } else {
                $player = $player[0];
                $hasDoneThisPool = $poolCompletionRepository->findCorrespondance([
                    "player" => $player->getId(),
                    "pool" => $pool->getId()
                ]);
            }

            if (count($hasDoneThisPool)) {
                $song = $hasDoneThisPool[0]->getSong();
            } else {
                $poolCompletion = new PoolCompletion();


                $songs = $pool->getSongs();
                $song = $songs[array_rand($songs->toArray())];
                $poolCompletion->setSong($song);
                $poolCompletion->setPlayer($player);
                $poolCompletion->setPool($pool);
                $poolCompletion->setCreatedAt(new \DateTime());

                $entityManager->persist($poolCompletion);
                $entityManager->flush();
            }

        }


        $file = $song->getFile();
        $location = $urlGenerator->generate("app_files", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $location = $location . str_replace("/public/", "", $file->getPublicPath() . '/' . $file->getRealPath());
        $song->setUrl($location);

        return ["pool" => $pool, "song" => $song];
    }
}
