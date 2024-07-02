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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{

       public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private PoolCompletionRepository $poolCompletionRepository
    ) {

    }
    private function downloadByDayChart(){
            $period = new DatePeriod(
        new DateTime('2024-06-24 18:03:27.000'),
        new DateInterval('P1D'),
        new DateTime(),
    );
    $dates = [];
    $stats = [];
    
    foreach ($period as $key => $value) {
    $dates[] = $value->format('Y-m-d');
    }
    foreach($dates as $key => $value){

        if(isset( $dates[$key + 1])){
            $stats[] = $this->poolCompletionRepository->getStatistics($value, $dates[$key + 1])[0][1];

        }
    }

            // dd($stats);
            // dd($pcomp);
           $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        // ...set chart data and options somehow

        $chart->setData([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Download',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' =>$stats,
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


        private function songRepartitionChart(){
            $period = new DatePeriod(
        new DateTime('2024-06-24 18:03:27.000'),
        new DateInterval('P1D'),
        new DateTime(),
    );
    $dates = [];
    $stats = [];
    
    foreach ($period as $key => $value) {
    $dates[] = $value->format('Y-m-d');
    }
    foreach($dates as $key => $value){

        if(isset( $dates[$key + 1])){
            $stats[] = $this->poolCompletionRepository->getStatistics($value, $dates[$key + 1])[0][1];

        }
    }

            // dd($stats);
            // dd($pcomp);
           $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        // ...set chart data and options somehow

        $chart->setData([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Download',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' =>$stats,
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
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(PoolCompletionCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
          

        return $this->render('admin/dashboard.html.twig', ["downloadByDayChart" => $this->downloadByDayChart(),"songRepartitionChart" => $this->songRepartitionChart()]);
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
            MenuItem::linkToCrud('Pool Completion', 'fa fa-tags', PoolCompletion::class),
            // MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', BlogPost::class),

            // MenuItem::section('Users'),
            // MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
            // MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
        ];
    

        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureAssets(): Assets
{
    $assets = parent::configureAssets();

    $assets->addWebpackEncoreEntry('app');

    return $assets;
}
}
