<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/profile/edit', name: 'app_edit')]
    public function edit(): Response
    {
        return $this->render('profile/edit.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/profile/history', name: 'app_history')]
    public function history(): Response
    {
        return $this->render('profile/history.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/profile/delete', name: 'app_delete')]
    public function delete(): Response
    {
        return $this->render('profile/delete.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
