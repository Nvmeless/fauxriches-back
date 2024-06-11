<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PoolRepository;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JackpotController extends AbstractController
{
    #[Route('/jackpot', name: 'app_jackpot')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/JackpotController.php',
        ]);
    }


        #[Route('api/jackpot/{code}', name: 'jackpot.roll')]
    public function roll(string $code,EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PoolRepository $poolRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $playerIp = $request->getClientIp();
        $player = $playerRepository->findBy(["ip" => $playerIp]);
        if(count($player)){
            $player = $player[0];
            $song = $player->getSong();
            $song->setDone(true);
            
        } else {
            $pool = $poolRepository->findBy(["code" => $code]);
            if(count($pool)){
                $songs = $pool[0]->getSongs();
                $song = $songs[array_rand($songs->toArray())];
                $song->setDone(false);
                $player = new Player();
                $player->setIp($playerIp);
                $player->setSong($song);
                $entityManager->persist($player);
                $entityManager->flush();
            }
        }
        $jsonSongs = $serializer->serialize($song, 'json',["groups" => "getSongs"]);
        return new JsonResponse($jsonSongs, Response::HTTP_OK,[], true);
    }
}
