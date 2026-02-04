<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CoachRepository;
use App\Repository\VideosRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookingType;
use App\Entity\Coaching;
use Doctrine\ORM\EntityManagerInterface;

final class CoachingController extends AbstractController
{
    #[Route('/coaching', name: 'app_coaching')]
    public function index(CoachRepository $coachRepository, VideosRepository $videosRepository): Response
    {
        $coachs = $coachRepository->findAll();

        return $this->render('coaching/index.html.twig', [
            'coachs' => $coachs,
        ]);
    }


    #[Route('/coaching/planning', name: 'app_planning')]
    public function planning(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('coaching/planning.html.twig', [
            'controller_name' => 'CoachingController',
        ]);
    }


    #[Route('/coaching/planning/booking', name: 'app_booking')]
    public function booking(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $coaching = new Coaching();
        $form = $this->createForm(BookingType::class, $coaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $month = $form->get('month')->getData();
            $day   = $form->get('day')->getData();
            $time  = $form->get('time')->getData();
            $type  = $form->get('coaching_type')->getData();

            $year = (int) date('Y');

            $datetime = new \DateTime(
                sprintf('%d-%02d-%02d %s', $year, $month, $day, $time)
            );

            $coaching->setDatetime($datetime);
            $coaching->setUser($user);
            $coaching->setStatus('waiting');
            $coaching->setCoachingType($type);

            $em->persist($coaching);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('coaching/booking.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
