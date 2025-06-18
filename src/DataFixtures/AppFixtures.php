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

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $pools = [];

        $pools[1] = [
            "name" => "Twisted Planet x Faux Riches Perdent les pedales !",
            "code" => "fdlm4rtprn",
            "edition" => '2024: Faux riches perd les pedales'

        ];

        $pools[2] = [
            "name" => "Faux Riches Perd les pedales !",
            "code" => "lyn4r",
            "edition" => '2024: Faux riches perd les pedales'

        ];
        $pools[3] = [
            "name" => "Radio Active x Faux Riches Perdent les pedales !",
            "code" => "lyn4rradio",
            "edition" => '2024: Faux riches perd les pedales'
        ];
        $pools[4] = [
            "name" => "Critical Hit !",
            "code" => "crtclht4r",
            "edition" => '2025: Faux riches a un succes critique'
        ];
        $pools[5] = [
            "name" => "Succes Critique !",
            "code" => "4rsccscrtq4r",
            "edition" => '2025: Faux riches a un succes critique'
        ];
        $newPools = [];
        foreach ($pools as $key => &$pool) {
            $data = $pool;
            $pool = new Pool();

            $pool->setCode($data['code']);
            $pool->setName($data['name']);
            $pool->setEdition($data['edition']);
            $newPools[$key] = $pool;
            $manager->persist($pool);


        }
        $pools = $newPools;
        $songs = [
            [
                "name" => "Mar.Tu - Aprilbeat (Marcel Turenne edit)",
                "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
                "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
                "pools" => [1, 3],
                "rarity" => 1
            ],
            [
                "name" => "Baxter Dury - Miami ( Kiss Husky - How successfull i am edit)",
                "realName" => "Baxter Dury - Miami ( Kiss Husky - How successfull i am edit).wav",
                "filePath" => 'Baxter-Dury---Miami-(-Kiss-Husky---How-successfull-i-am-edit).wav',
                "pools" => [1, 2, 3],
                "rarity" => 1
            ],
            [
                "name" => 'Torpedo Boyz - Your input was not correct (ne.mo re-edit)',
                "realName" => 'Torpedo Boyz - Your input was not correct (ne.mo re-edit).wav',
                "filePath" => "Torpedo-Boyz---Your-input-was-not-correct-(ne-mo-re-edit).wav",
                "pools" => [2, 3, 1],
                "rarity" => 1
            ],
            [
                "name" => 'Dyvad - LSTP001',
                "realName" => 'Dyvad - LSTP001.wav',
                "filePath" => "Dyvad---LSTP001.wav",
                "pools" => [1, 3],
                "rarity" => 1
            ],
            [
                "name" => 'Time Tweaker - Hôtesse 2 l\'air',
                "realName" => 'Time Tweaker - Hôtesse 2 l\'air.wav',
                "filePath" => "Time-Tweaker---Hotesse-2-l-air.wav",
                "pools" => [1, 2],
                "rarity" => 1
            ],
            [
                "name" => "S. Fidelity - Something Good (Karaba Edit)",
                "realName" => "S. Fidelity - Something Good (Karaba Edit).wav",
                "filePath" => "S-Fidelity---Something-Good-(Karaba-Edit).wav",
                "pools" => [1, 2],
                "rarity" => 1
            ],
            [
                "name" => "LUIS HAYES - i don't wanna be your friends",
                "realName" => "LUIS HAYES - i don't wanna be your friends.wav",
                "filePath" => "LUIS-HAYES---i-don-t-wanna-be-your-friends.wav",
                "pools" => [3, 2],
                "rarity" => 1
            ],
            [
                "name" => "Sombionx - Destiny (Yoffers Dub edit)",
                "realName" => "Sombionx - Destiny (Yoffers Dub edit).wav",
                "filePath" => "Sombionx---Destiny-(Yoffers-Dub-edit).wav",
                "pools" => [2, 3],
                "rarity" => 1
            ],
        ];

        $songs = [
            [
                "name" => "Baxter Dury - Miami ( Kiss Husky - How successfull i am edit)",
                "realName" => "Baxter Dury - Miami ( Kiss Husky - How successfull i am edit).wav",
                "filePath" => 'Baxter-Dury---Miami-(-Kiss-Husky---How-successfull-i-am-edit).wav',
                "pools" => [5],
                "rarity" => 5
            ],
            [
                "name" => 'Torpedo Boyz - Your input was not correct (ne.mo re-edit)',
                "realName" => 'Torpedo Boyz - Your input was not correct (ne.mo re-edit).wav',
                "filePath" => "Torpedo-Boyz---Your-input-was-not-correct-(ne-mo-re-edit).wav",
                "pools" => [5],
                "rarity" => 5
            ],
            [
                "name" => 'Time Tweaker - Hôtesse 2 l\'air',
                "realName" => 'Time Tweaker - Hôtesse 2 l\'air.wav',
                "filePath" => "Time-Tweaker---Hotesse-2-l-air.wav",
                "pools" => [5],
                "rarity" => 5
            ],
            [
                "name" => "S. Fidelity - Something Good (Karaba Edit)",
                "realName" => "S. Fidelity - Something Good (Karaba Edit).wav",
                "filePath" => "S-Fidelity---Something-Good-(Karaba-Edit).wav",
                "pools" => [5],
                "rarity" => 5
            ]
        ];

        $songs[] = [
            "name" => "TEST LEVEL 1",
            "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
            "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
            "pools" => [5],
            "rarity" => 1
        ];
        $songs[] = [
            "name" => "TEST 2 LEVEL 1",
            "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
            "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
            "pools" => [5],
            "rarity" => 1
        ];
        $songs[] = [
            "name" => "TEST  LEVEL 2",
            "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
            "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
            "pools" => [5],
            "rarity" => 2
        ];
        $songs[] = [
            "name" => "TEST 2 LEVEL 2",
            "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
            "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
            "pools" => [5],
            "rarity" => 2
        ];
        $songs[] = [
            "name" => "TEST LEVEL 3",
            "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
            "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
            "pools" => [5],
            "rarity" => 3
        ];
        $songs[] = [
            "name" => "TEST LEVEL 4",
            "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
            "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
            "pools" => [5],
            "rarity" => 4
        ];
        $songs[] = [
            "name" => "TEST LEVEL 5",
            "realName" => "Mar.Tu - Aprilbeat (Marcel Turenne edit).wav",
            "filePath" => 'Mar-Tu---Aprilbeat-(Marcel-Turenne-edit).wav',
            "pools" => [5],
            "rarity" => 5
        ];

        $songList = [];
        foreach ($songs as $songData) {
            $file = new DownloadedFile();
            $song = new Song();
            $file->setMimeType("audio/wav");
            $file->setCreatedAt(new DateTime());
            $file->setRealPath($songData['filePath']);
            $file->setPublicPath("files/songs");
            $file->setStatus("on");
            $file->setRealName($songData['realName']);
            $song->setName($songData['name']);
            $song->setRarity($songData["rarity"]);
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

        for ($i = 0; $i < 10; $i++) {
            $player = new Player();
            $player->setIp($this->faker->ipv4());
            $manager->persist($player);

            $players[] = $player;

        }
        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $poolCompletion = new PoolCompletion();
            $song = $songList[array_rand($songList)];
            $poolCompletion->setCreatedAt($this->faker->dateTimeBetween('-1 week', '+1 week'))
                ->setPlayer($players[array_rand($players)])
                ->setPool($pools[array_rand($pools)])
                ->setSong($song)
                ->setIsReroll($song->getRarity() > 1);
            $manager->persist($poolCompletion);

        }
        $manager->flush();

    }


}
