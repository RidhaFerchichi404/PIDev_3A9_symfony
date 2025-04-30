<?php

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    #[Route('/dashboard', name: 'app_admin_dashboard_index')]
    public function index(PostRepository $postRepository, CommentRepository $commentRepository): Response
    {
        // Get statistics
        $totalPosts = $postRepository->count([]);
        $totalComments = $commentRepository->count([]);
        
        // Get posts by type
        $postsByType = $postRepository->countByType();
        
        // Get recent posts
        $recentPosts = $postRepository->findBy([], ['dateU' => 'DESC'], 5);
        
        // Get recent comments
        $recentComments = $commentRepository->findBy([], ['date' => 'DESC'], 5);

        // Create pie chart for post types
        $pieChart = new PieChart();
        $pieChartData = [['Type', 'Count']];
        
        // Only add data if we have post types
        if (count($postsByType) > 0) {
            foreach ($postsByType as $type) {
                $pieChartData[] = [$type['type'], (int)$type['count']];
            }
        } else {
            // Add dummy data if no post types exist
            $pieChartData[] = ['No posts', 1];
        }
        
        $pieChart->getData()->setArrayToDataTable($pieChartData);
        
        // Configure pie chart
        $pieChart->getOptions()->setTitle('Posts by Type');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(600);
        $pieChart->getOptions()->setIs3D(true);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        // Create bar chart for comments by post (top 5 most commented)
        $barChart = new BarChart();
        $mostCommentedPosts = $postRepository->findMostCommented(1, 5);
        $barChartData = [['Post', 'Comments']];
        
        // Only add data if we have commented posts
        if (count($mostCommentedPosts) > 0) {
            foreach ($mostCommentedPosts as $post) {
                $commentCount = count($post->getComments());
                $postTitle = substr($post->getDescription(), 0, 30) . '...';
                $barChartData[] = [$postTitle, $commentCount];
            }
        } else {
            // Add dummy data if no commented posts
            $barChartData[] = ['No comments', 0];
        }
        
        $barChart->getData()->setArrayToDataTable($barChartData);
        
        // Configure bar chart
        $barChart->getOptions()->setTitle('Most Commented Posts');
        $barChart->getOptions()->getHAxis()->setTitle('Comments');
        $barChart->getOptions()->getVAxis()->setTitle('Posts');
        $barChart->getOptions()->setWidth(600);
        $barChart->getOptions()->setHeight(400);
        $barChart->getOptions()->getBar()->setGroupWidth('75%');
        $barChart->getOptions()->getLegend()->setPosition('none');

        // Create a column chart for blog statistics
        $statsChart = new ColumnChart();
        $statsChartData = [
            ['Metric', 'Count', ['role' => 'style']],
            ['Posts', $totalPosts, '#4285F4'],
            ['Comments', $totalComments, '#34A853']
        ];
        
        $statsChart->getData()->setArrayToDataTable($statsChartData);
        
        // Configure column chart
        $statsChart->getOptions()
            ->setTitle('Blog Statistics')
            ->setHeight(300)
            ->setWidth(500)
            ->setColors(['#4285F4', '#34A853'])
            ->setFontName('Arial')
            ->getLegend()->setPosition('none');

        // Configure axes
        $statsChart->getOptions()->getHAxis()->setTitle('Metric');
        $statsChart->getOptions()->getHAxis()->getTitleTextStyle()->setBold(true);
        $statsChart->getOptions()->getVAxis()->setTitle('Count');
        $statsChart->getOptions()->getVAxis()->getTitleTextStyle()->setBold(true);

        return $this->render('postandcomment/admin/dashboard/index.html.twig', [
            'totalPosts' => $totalPosts,
            'totalComments' => $totalComments,
            'postsByType' => $postsByType,
            'recentPosts' => $recentPosts,
            'recentComments' => $recentComments,
            'pieChart' => $pieChart,
            'barChart' => $barChart,
            'statsChart' => $statsChart,
        ]);
    }
} 