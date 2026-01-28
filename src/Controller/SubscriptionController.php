<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_subscription')]
    public function index(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        $subscription = $user ? $user->getSubscription() : null;

        return $this->render('subscription/index.html.twig', [
            'subscription' => $subscription,
        ]);
    }

    #[Route('/subscribe/{duration}', name: 'app_subscribe')]
    public function subscribe(int $duration, EntityManagerInterface $em): RedirectResponse
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!in_array($duration, [1, 6, 12])) {
            throw $this->createNotFoundException();
        }

        $startDate = new \DateTime();
        $endDate = (clone $startDate)->modify('+' . $duration . ' months');

        $subscription = new Subscription();
        $subscription->setUser($user);

        $subscription->setStartDate($startDate);
        $subscription->setEndDate($endDate);
        $subscription->setStatus($duration . '_months');
        $roles = $user->getRoles();
        if (!in_array('ROLE_ABONNE', $roles)) {
            $roles[] = 'ROLE_ABONNE';
            $user->setRoles($roles);
        }

        $em->persist($subscription);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
    #[Route('/unsubscribe', name: 'app_unsubscribe')]
    public function unsubscribe(EntityManagerInterface $em): RedirectResponse
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $subscription = $user->getSubscription();

        if ($subscription) {
            $roles = $user->getRoles();
            if (($key = array_search('ROLE_ABONNE', $roles)) !== false) {
                unset($roles[$key]);
            }
            if (empty($roles)) {
                $roles[] = 'ROLE_JOUEUR';
            }
            $user->setRoles(array_values($roles));


            $em->remove($subscription);
            $em->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
