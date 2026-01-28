<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CoachRepository;
use App\Repository\VideosRepository;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(CoachRepository $coachRepository, VideosRepository $videosRepository): Response
    {
        $coachs = $coachRepository->findAll();

        $videoId = 6;
        $video = $videosRepository->find($videoId);

        return $this->render('home/index.html.twig', [
            'coachs' => $coachs,
            'video' => $video
        ]);
    }
}
