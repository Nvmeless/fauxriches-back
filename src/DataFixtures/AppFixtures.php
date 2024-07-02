<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Pool;
use App\Entity\Song;
use Faker\Generator;
use App\Entity\Player;
use App\Entity\DownloadedFile;
use App\Entity\PoolCompletion;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{


        /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $pools= [];

        $pools[1] = [
            "name" => "Twisted Planet x Faux Riches Perdent les pedales !",
            "code"=> "fdlm4rtprn",

        ];

        $pools[2] = [
            "name" => "Faux Riches Perd les pedales !",
            "code"=> "lyn4r",

        ];
        $pools[3] = [
            "name" => "Radio Active x Faux Riches Perdent les pedales !",
            "code"=> "lyn4rradio",
        ];
        $newPools = [];
        foreach ($pools as $key => &$pool) {
            $data = $pool;
            $pool = new Pool();
            
            $pool->setCode($data['code']);
            $pool->setName($data['name']);
            $newPools[$key] = $pool;
            $manager->persist($pool);

            
        }
        $pools = $newPools;
        $songs = [
            [
                "name" =>  "Mar.Tu - Aprilbeat (Marcel Turenne edit)",
                "realName" =>  "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
                "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav', 
                "pools" => [1, 3]
            ],
            [
                "name" =>  "Baxter Dury - Miami ( Kiss Husky - How successfull i am edit)",
                "realName" =>  "Baxter Dury - Miami ( Kiss Husky - How successfull i am edit).wav",
                "filePath" => 'Baxter-Dury---Miami-(-Kiss-Husky---How-successfull-i-am-edit).wav', 
                "pools" => [1,2,3]
            ],
            [
                "name" =>  'Torpedo Boyz - Your input was not correct (ne.mo re-edit)',
                "realName" =>  'Torpedo Boyz - Your input was not correct (ne.mo re-edit).wav',
                "filePath" => "Torpedo-Boyz---Your-input-was-not-correct-(ne-mo-re-edit).wav",
                "pools" => [2,3,1]
            ],
            [
                "name" =>  'Dyvad - LSTP001',
                "realName" =>  'Dyvad - LSTP001.wav',
                "filePath" => "Dyvad---LSTP001.wav",
                "pools" => [1,3]
            ],
            [
                "name" =>  'Time Tweaker - Hôtesse 2 l\'air',
                "realName" =>  'Time Tweaker - Hôtesse 2 l\'air.wav',
                "filePath" => "Time-Tweaker---Hotesse-2-l-air.wav",
                "pools" => [1,2]
            ],
            [
                "name" =>  "S. Fidelity - Something Good (Karaba Edit)",
                "realName" =>  "S. Fidelity - Something Good (Karaba Edit).wav",
                "filePath" => "S-Fidelity---Something-Good-(Karaba-Edit).wav",
                "pools" => [1,2]
            ],
            [
                "name" =>  "LUIS HAYES - i don't wanna be your friends",
                "realName" =>  "LUIS HAYES - i don't wanna be your friends.wav",
                "filePath" => "LUIS-HAYES---i-don-t-wanna-be-your-friends.wav",
                "pools" => [3,2]
            ],
            [
                "name" =>  "Sombionx - Destiny (Yoffers Dub edit)",
                "realName" =>  "Sombionx - Destiny (Yoffers Dub edit).wav",
                "filePath" => "Sombionx---Destiny-(Yoffers-Dub-edit).wav",
                "pools" => [2,3]
            ],
        ];
        $songList = [];
        foreach($songs as  $songData){
            $file = new DownloadedFile();
            $song = new Song();
            $file->setMimeType("audio/wav");
            $file->setCreatedAt(new DateTime());
            $file->setRealPath($songData['filePath']);
            $file->setPublicPath("files/songs");
            $file->setStatus("on");
            $file->setRealName($songData['realName']);
            $song->setName($songData['name']);
            $file->setUpdatedAt(new DateTime());
            $file->setFileSize(0);
            foreach ($songData["pools"] as $pool) {
                $song->addPool($pools[$pool]);
            }
            $song->setFile($file);
            $manager->persist($song);
            $manager->persist($file);
            $songList[] = $song;
        }
    
        $manager->flush();

        $players = [];

        for($i = 0; $i < 10; $i++){
            $player = new Player();
            $player->setIp($this->faker->ipv4());
            $manager->persist($player);

            $players[] = $player;
            
        }
        $manager->flush();

        for($i = 0; $i < 100; $i++){
            $poolCompletion = new PoolCompletion();
            $poolCompletion->setCreatedAt($this->faker->dateTimeBetween('-1 week', '+1 week'))
            ->setPlayer($players[array_rand($players)])
            ->setPool($pools[array_rand($pools)])
            ->setSong($songList[array_rand($songList)]);
            $manager->persist($poolCompletion);

        }
        $manager->flush();

    }

    
}
