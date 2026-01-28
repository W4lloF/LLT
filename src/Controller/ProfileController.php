<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ProfileEditType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


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
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            $em->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/profile/history', name: 'app_history')]
    public function history(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        $coachings = $user ? $user->getCoachings() : [];

        return $this->render('profile/history.html.twig', [
            'coachings' => $coachings,
        ]);
    }


    #[Route('/profile/coachings', name: 'app_coachings')]
    public function userCoachings(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $coachings = $user->getCoachings();

        return $this->render('profile/coachings.html.twig', [
            'coachings' => $coachings,
        ]);
    }


    #[Route('/profile/delete', name: 'app_delete')]
    public function delete(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Request $request): Response
    {

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }


    #[Route('/profile/feedback', name: 'app_feedback')]
    public function feedback(): Response
    {
        return $this->render('profile/feedback.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    #[Route('/profile/coach/users', name: 'app_listcoach')]
    public function userList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('profile/list.html.twig', [
            'users' => $users,
            'title' => 'Liste des utilisateurs'
        ]);
    }
    #[Route('/profile/coach/createfeedback/{id}', name: 'app_createfeedback')]
    public function createFeedback(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        /** @var \App\Entity\User $userConnected */
        $userConnected = $this->getUser();
        $coach = $userConnected->getCoach();
        $user = $userRepository->find($id);

        $feedback = new \App\Entity\Feedback();
        $feedback->setUser($user);

        $feedback->setCoach($coach);
        $feedback->setCreationDate(new \DateTime());

        $form = $this->createForm(\App\Form\FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($feedback);
            $em->flush();

            return $this->redirectToRoute('app_listcoach', ['id' => $id]);
        }

        return $this->render('profile/createfeedback.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
    #[Route('/profile/coach/feedbacks', name: 'app_coachfeedbacks')]
    public function coachFeedbacks(EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $userConnected */
        $userConnected = $this->getUser();
        $coach = $userConnected->getCoach();

        $feedbacks = $coach->getFeedback();

        return $this->render('profile/feedbackscoach.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }
    #[Route('/profile/coach/coachings', name: 'app_coachcoachings')]
    public function coachCoachings(EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $userConnected */
        $userConnected = $this->getUser();
        $coach = $userConnected->getCoach();

        $coachings = $coach->getCoachings()->filter(fn($c) => $c->getStatus() === 'waiting');

        return $this->render('profile/coachcoachings.html.twig', [
            'coachings' => $coachings,
        ]);
    }
    #[Route('/profile/coach/history', name: 'app_coachhistory')]
    public function coachhistory(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $coach = $user->getCoach();

        if ($coach) {
            $coachings = $coach->getCoachings()->filter(fn($c) => $c->getStatus() === 'done');
        } else {
            $coachings = [];
        }

        return $this->render('profile/coachhistory.html.twig', [
            'coachings' => $coachings,
        ]);
    }
}
