<?php

namespace App\Controller\Admin;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Entity\PoolCompletion;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\PoolCompletionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use App\Controller\Admin\PoolCompletionCrudController;
use App\Repository\PlayerRepository;
use App\Repository\PoolRepository;
use App\Repository\SongRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{

    public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private PoolCompletionRepository $poolCompletionRepository,
        private PoolRepository $poolRepository,
        private SongRepository $songRepository,
        private PlayerRepository $playerRepository
    ) {
    }
    private function rand_color()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
    private function downloadByDayChart()
    {
        $period = new DatePeriod(
            new DateTime('2024-06-17 00:00:01.000'),
            new DateInterval('P1D'),
            new DateTime(),
        );
        $dates = [];
        $stats = [];

        foreach ($period as $key => $value) {
            $dates[] = $value->format('Y-m-d');
        }
        foreach ($dates as $key => $value) {

            if (isset($dates[$key + 1])) {
                $stats[] = $this->poolCompletionRepository->getStatistics($value, $dates[$key + 1])[0][1];
            }
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Download',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $stats,
                    'color' => "white"
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($stats) + 5,
                ],
            ],
        ]);
        return $chart;
    }
    private function downloadByPoolsChart()
    {
        $poolsEntities = $this->poolRepository->findAll();
        $pools = [];
        $poolarr = [];
        foreach ($poolsEntities as $key => $pool) {
            $pools[$pool->getId()] = $pool->getName();
            $poolarr[] = $pool->getName();
        }
        $stats = [];
        $colors = [];
        foreach ($pools as $poolId => $pool) {
            $stats[] = count($this->poolCompletionRepository->findBy(["pool" => $poolId]));
            $colors[] = $this->rand_color();
        }


        return $this->generatePolarChart(['indexes' => $poolarr, "stats" => $stats, "colors" => $colors]);
    }
    private function generatePolarChart($datas)
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_POLAR_AREA);

        $chart->setData([
            'labels' => $datas["indexes"],
            'datasets' => [
                [
                    'label' => 'Download',
                    'backgroundColor' => $datas["colors"],
                    'borderColor' => $datas["colors"],
                    'data' => $datas["stats"],

                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($datas["stats"]) + 5,
                ],
            ],
        ]);
        return $chart;
    }
    private function songRepartitionChart()
    {

        $songsEntities = $this->songRepository->findAll();
        $songs = [];
        $songarr = [];
        foreach ($songsEntities as $key => $song) {
            $songs[$song->getId()] = $song->getName();
            $songarr[] = $song->getName();
        }

        $stats = [];
        $colors = [];
        foreach ($songs as $songId => $song) {
            $stats[] = count($this->poolCompletionRepository->findBy(["song" => $songId]));
            $colors[] = $this->rand_color();
        }
        return $this->generatePolarChart(['indexes' => $songarr, "stats" => $stats, "colors" => $colors]);
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $countDownload = count($this->poolCompletionRepository->findAll());
        $countPeople = count($this->playerRepository->findAll());

        return $this->render('admin/dashboard.html.twig', ["countDownload" => $countDownload, "countPlayers" => $countPeople, "downloadByPoolsChart" => $this->downloadByPoolsChart(), "downloadByDayChart" => $this->downloadByDayChart(), "songRepartitionChart" => $this->songRepartitionChart(), ""]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Fauxriches Back');
    }

    public function configureMenuItems(): iterable
    {

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('FDLM'),
            // MenuItem::linkToCrud('Pool Completion', 'fa fa-tags', PoolCompletion::class),
        ];
    }

    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();

        $assets->addWebpackEncoreEntry('app');
        $assets->addCssFile('css/admin.css');
        return $assets;
    }
}
