<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VideosRepository;

final class VideoController extends AbstractController
{
    #[Route('/video', name: 'app_video')]
    public function index(VideosRepository $videosRepository): Response
    {
        $videos = $videosRepository->findAll();

        $user = $this->getUser();
        $userRoles = $user ? $user->getRoles() : [];

        return $this->render('video/index.html.twig', [
            'videos' => $videos,
            'userRoles' => $userRoles,
        ]);
    }
}
