<?php

namespace App\Controller;

use App\Form\VideoType;
use App\Repository\CoachingRepository;
use App\Repository\VideosRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    #[Route('/admin/list', name: 'app_list')]
    public function list(): Response
    {
        return $this->render('admin/list.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    #[Route('/admin/videolist', name: 'app_videolist')]
    public function videolist(VideosRepository $videosRepository): Response
    {
        $videos = $videosRepository->findAll();

        return $this->render('admin/videolist.html.twig', [
            'videos' => $videos,
        ]);
    }


    #[Route('/admin/videolist/delete/{id}', name: 'app_videodelete')]
    public function videodelete(int $id, VideosRepository $videosRepository, EntityManagerInterface $em): Response
    {
        $video = $videosRepository->find($id);

        $em->remove($video);
        $em->flush();

        return $this->redirectToRoute('app_videolist');
    }


    #[Route('/admin/videolist/edit/{id}', name: 'app_videoedit')]
    public function videoedit(int $id, VideosRepository $videosRepository, Request $request, EntityManagerInterface $em): Response
    {
        $video = $videosRepository->find($id);

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_videolist');
        }

        return $this->render('admin/videoedit.html.twig', [
            'form' => $form->createView(),
            'video' => $video,
        ]);
    }


    #[Route('/admin/videolist/add', name: 'app_videoadd')]
    public function videoadd(Request $request, EntityManagerInterface $em): Response
    {
        $video = new \App\Entity\Videos();

        $form = $this->createForm(\App\Form\VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($video);
            $em->flush();
            return $this->redirectToRoute('app_videolist');
        }

        return $this->render('admin/videoedit.html.twig', [
            'form' => $form->createView(),
            'video' => $video,
        ]);
    }


    #[Route('/admin/videoedit/choose', name: 'app_videogetid_edit')]
    public function getIDVideoEdit(Request $request, VideosRepository $videosRepository): Response
    {
        $form = $this->createForm(\App\Form\GetIDVideoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->getData()['id'];
            $video = $videosRepository->find($id);

            return $this->redirectToRoute('app_videoedit', ['id' => $id]);
        }

        return $this->render('admin/GetIDVideoEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/videodelete/choose', name: 'app_videogetid_delete')]
    public function getIDVideoDelete(Request $request, VideosRepository $videosRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(\App\Form\GetIDVideoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->getData()['id'];
            $video = $videosRepository->find($id);

            return $this->redirectToRoute('app_videodelete', ['id' => $id]);
        }

        return $this->render('admin/GetIDVideoDelete.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/users', name: 'app_list')]
    public function userList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/list.html.twig', [
            'users' => $users,
            'title' => 'Liste des utilisateurs'
        ]);
    }


    #[Route('/admin/search', name: 'app_search')]
    public function getUsernameForm(Request $request): Response
    {
        $form = $this->createForm(\App\Form\GetUsernameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->getData()['username'];
            return $this->redirectToRoute('app_userresult', ['username' => $username]);
        }

        return $this->render('admin/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/result/{username}', name: 'app_userresult')]
    public function showUser(string $username, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        return $this->render('admin/result.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/admin/delete/choose', name: 'app_search_delete')]
    public function deleteUserForm(Request $request): Response
    {
        $form = $this->createForm(\App\Form\GetUsernameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->getData()['username'];
            return $this->redirectToRoute('app_userdelete', ['username' => $username]);
        }

        return $this->render('admin/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/delete/{username}', name: 'app_userdelete')]
    public function deleteUser(
        string $username,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response {
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user) {
            if ($user->getSubscription()) {
                $em->remove($user->getSubscription());
            }

            if ($user->getCoach()) {
                $em->remove($user->getCoach());
            }

            foreach ($user->getFeedback() as $feedback) {
                $em->remove($feedback);
            }

            foreach ($user->getCoachings() as $coaching) {
                $em->remove($coaching);
            }

            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('app_list');
    }
    #[Route('/admin/coachings', name: 'app_coachinglist')]
    public function listCoachingsWaiting(CoachingRepository $coachingRepository): Response
    {
        $coachings = $coachingRepository->findBy(['status' => 'waiting']);

        return $this->render('admin/coachinglist.html.twig', [
            'coachings' => $coachings,
        ]);
    }

    #[Route('/admin/coachingsaccept/{id}', name: 'app_coachingsaccept')]
    public function acceptCoaching(int $id, CoachingRepository $coachingRepository, EntityManagerInterface $em): Response
    {
        $coaching = $coachingRepository->find($id);

        if ($coaching && $coaching->getStatus() === 'waiting') {
            $coaching->setStatus('accepted');
            $em->flush();
        }

        return $this->redirectToRoute('app_coachingslist');
    }

    #[Route('/admin/coachings/delete/choose', name: 'app_coachinggetid_delete')]
    public function GetIDCoachingDelete(Request $request): Response
    {
        $form = $this->createForm(\App\Form\GetCoachingIDType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->getData()['id'];
            return $this->redirectToRoute('app_coachingsdelete', ['id' => $id]);
        }

        return $this->render('admin/GetIDCoachingDelete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/coachingsdelete/{id}', name: 'app_coachingsdelete')]
    public function deleteCoaching(int $id, CoachingRepository $coachingRepository, EntityManagerInterface $em): Response
    {
        $coaching = $coachingRepository->find($id);
        if ($coaching) {
            $em->remove($coaching);
            $em->flush();
        }

        return $this->redirectToRoute('app_coachinglist');
    }
}
