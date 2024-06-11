<?php

namespace App\DataFixtures;

use App\Entity\Pool;
use App\Entity\Song;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $pools= [];
        for($i = 0; $i < 3; $i++){
                    $pool = new Pool();

                    $pool->setCode("Test".$i);
                    $pools[]= $pool;
        $manager->persist($pool);


                }
                        for($i = 0; $i < 10; $i++){
                    $song = new Song();
                    $song->setName("Song #" . $i);
                    $song->addPool($pools[array_rand($pools)]);
        $manager->persist($song);
        }
        $manager->flush();
    }
}
