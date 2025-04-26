<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkoutCalendarController extends AbstractController
{
    #[Route('/workout/calendar', name: 'app_workout_calendar')]
    public function index(): Response
    {
        return $this->render('workout_calendar.html.twig');
    }
} 