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

        $pools[1] = [
            "name" => "Twisted Planet x Faux Riches Perdent les pedales !",
            "code"=> "",

        ];

        $pools[2] = [
            "name" => "Faux Riches Perd les pedales !",
            "code"=> "lyn4r",

        ];
        $pools[3] = [
            "name" => "Radio Active x Faux Riches Perdent les pedales !",
            "code"=> "lyn4rradio",
        ];

        foreach ($pools as $key => &$pool) {
            $data = $pool;
            $pool = new Pool();
           
            $pool->setCode($data['code']);
            $pool->setName($data['name']);
            $manager->persist($pool);
        }



        





        for($i = 1; $i < 10; $i++){
            $song = new Song();
            $song->setName("Song #" . $i);
            $song->addPool($pools[array_rand($pools)]);
            $manager->persist($song);
        }
        $manager->flush();
    }

    
}
