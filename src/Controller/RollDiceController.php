<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\PoolCompletion;
use App\Repository\PoolRepository;
use App\Repository\PlayerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PoolCompletionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RollDiceController extends AbstractController
{
    #[Route('api/test', name: 'dice.roll.test')]

    public function index(): Response
    {

        $oldDate = new DateTime("2025-02-12");
        $cb = base_convert(($oldDate->format("d") / $oldDate->format("t")), 10, $oldDate->format("t"));
        $newDate = new DateTime();
        $data = [
            'controller_name' => "toto",
            // 'old' => $oldDate,
            "new" => $newDate,
            "m" => $oldDate->format("d"),
            "o" => $oldDate->format("t"),
            "calc" => ($oldDate->format("d") / $oldDate->format("t")) > 0.5,

        ];

        return $this->json(
            $data
        );
    }

    #[Route('dice/{code}', name: 'dice.roll.api')]
    public function rollApi(string $code, PoolCompletionRepository $poolCompletionRepository, EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PoolRepository $poolRepository, SerializerInterface $serializer, Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $result = $this->roll($code, $request, $poolCompletionRepository, $entityManager, $playerRepository, $poolRepository, $serializer, $urlGenerator);
        $return = [
            "pool" => $result["pool"],
            "song" => $result["song"],
            "rarity" => $result["rarity"],
            "won" => $result["won"],
        ];
        $jsonSongs = $serializer->serialize($return, 'json', ["groups" => "getSongs"]);
        return $this->render(
            'roll_dice/index.html.twig',
            [
                'roll' => $jsonSongs,
            ]
        );
        return new JsonResponse($jsonSongs, Response::HTTP_OK, [], true);
    }
    private function roll(
        string $code,
        Request $request,
        PoolCompletionRepository $poolCompletionRepository,
        EntityManagerInterface $entityManager,
        PlayerRepository $playerRepository,
        PoolRepository $poolRepository,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ): array {
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

                //Gestion de la rareté
                $rarity = $this->getRarity();
                if ($rarity > 1 && $rarity < 5) {
                    $rarity = 1;
                }
                $songs = $pool->getSongs();
                $hesGotThatLegendary = array_filter($poolCompletionRepository->findAllCorrespondance([
                    "player" => $player->getId(),
                    "pool" => $pool->getId()
                ]));
                $songsThatHesGot = [];
                foreach ($hesGotThatLegendary as $value) {
                    $songsThatHesGot[] = $value->getSong()->getId();
                }
                $leveled = array_filter($songs->toArray(), function ($song) use ($rarity, $songsThatHesGot) {

                    if ($rarity !== 5) {
                        return $song->getRarity() === $rarity;
                    }
                    return $song->getRarity() === $rarity && !in_array($song->getId(), $songsThatHesGot);
                });

                if (empty($leveled)) {
                    $leveled = array_filter($songs->toArray(), function ($song) use ($rarity) {

                        return $song->getRarity() === $rarity;

                    });
                }

                $song = $leveled[array_rand($leveled)];

                $poolCompletion = new PoolCompletion();
                $poolCompletion->setSong($song);
                $poolCompletion->setPlayer($player);
                $poolCompletion->setPool($pool);
                $poolCompletion->setCreatedAt(new \DateTime());
                $poolCompletion->setIsReroll($rarity === 5);

                $entityManager->persist($poolCompletion);
                $entityManager->flush();
            }

        }

        $won = $poolCompletionRepository->findAllCorrespondance([
            "player" => $player->getId(),
            "pool" => $pool->getId()
        ]);
        $file = $song->getFile();
        $location = $urlGenerator->generate("app_files", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $location = $location . str_replace("/public/", "", $file->getPublicPath() . '/' . $file->getRealPath());
        $song->setUrl($location);

        return ["pool" => $pool, "song" => $song, "rarity" => $song->getRarity(), "won" => $won];
    }


    private function getRarity()
    {
        $numberOfThrow = 1;
        // Common

        $rarity = 1;
        // Uncommon
        if ($this->takeAChanceFromPercent(25, ajustByThrow: $numberOfThrow)) {
            $rarity++;
        }

        // Rare
        if ($this->takeAChanceFromPercent(15, $numberOfThrow)) {
            $rarity++;
        }
        // Mythic
        if ($this->takeAChanceFromPercent(10, $numberOfThrow)) {
            $rarity++;
        }
        // Legendary
        if ($this->takeAChanceFromPercent(5, $numberOfThrow)) {
            $rarity++;
        }



        return $rarity;
    }
    private function takeAChanceFromPercent($percents, $ajustByThrow = 1)
    {
        //5
        $percents = ($percents * 100) / $ajustByThrow;

        $comparison = 10000 - $percents;
        if (rand(0, 10000) > $comparison) {
            return true;
        }
        return false;
    }
    // #[Route('/dice/{code}', name: 'dice.roll.app')]
    // public function getPage()
    // {

    //     return $this->render('roll_dice/index.html.twig');
    // }

}
