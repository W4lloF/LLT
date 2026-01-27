<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CoachingController extends AbstractController
{
    #[Route('/coaching', name: 'app_coaching')]
    public function index(): Response
    {
        return $this->render('coaching/index.html.twig', [
            'controller_name' => 'CoachingController',
        ]);
    }
    #[Route('/coaching/planning', name: 'app_planning')]
    public function planning(): Response
    {
        return $this->render('coaching/planning.html.twig', [
            'controller_name' => 'CoachingController',
        ]);
    }
    #[Route('/coaching/planning/booking', name: 'app_booking')]
    public function booking(): Response
    {
        return $this->render('coaching/booking.html.twig', [
            'controller_name' => 'CoachingController',
        ]);
    }
}
