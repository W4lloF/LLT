<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignupController extends AbstractController
{
    #[Route('/signup', name: 'app_signup')]
    public function signup(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ): Response {
        $user = new User();

        $form = $this->createForm(UserType::class, $user, [
            'include_pseudo' => true,
            'submit_label' => "S'inscrire",
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($userRepository->findOneBy(['email' => $user->getEmail()])) {
                $this->addFlash('error', 'Cet email est déjà utilisé');
                return $this->redirectToRoute('app_signup');
            }

            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );

            $user->setRoles([User::ROLE_JOUEUR]);
            $user->setRegistrationDate(new \DateTime());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a bien été créé !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('signup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
