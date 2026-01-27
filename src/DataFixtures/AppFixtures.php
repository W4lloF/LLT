<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Coach;
use App\Entity\Subscription;
use App\Entity\Videos;
use App\Entity\Coaching;
use App\Entity\Feedback;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $firstNames = ['Alice', 'Bob', 'Charlie', 'David', 'Emma'];
        $lastNames = ['Dupont', 'Martin', 'Durand', 'Leroy', 'Moreau'];
        $roles = ['SUBBED', 'JOUEURS', 'SUBBED', 'JOUEURS', 'ROLES_ADMIN'];

        $subscriptionStatuses = ['1 month', '6 month', '12 month'];
        $coachingStatuses = ['asked', 'confirmed', 'done'];

        $users = [];
        $coaches = [];
        $videos = [];

        // --- Création des utilisateurs ---
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $username = strtolower($firstNames[$i] . '.' . $lastNames[$i]);
            $user->setUsername($username);
            $user->setEmail($username . '@example.com');
            $user->setPassword(password_hash('Password123', PASSWORD_BCRYPT));
            $user->setRole($roles[$i]);
            $user->setRegistrationDate((new \DateTime())->modify('-' . rand(0, 365) . ' days'));

            // Coins cohérents selon le rôle
            $user->setCoin(0); // valeur par défaut

            switch ($roles[$i]) {
                case 'ROLES_ADMIN':
                    $user->setCoin(rand(100, 500));
                    break;
                case 'JOUEURS':
                    $user->setCoin(rand(50, 200));
                    break;
                case 'SUBBED':
                    $user->setCoin(rand(20, 100));
                    break;
            }

            $manager->persist($user);
            $users[] = $user;
        }

        // --- Création des coaches ---
        for ($i = 0; $i < 5; $i++) {
            $coach = new Coach();
            $coach->setPseudo('Coach_' . $i);
            $coach->setEmail('coach' . $i . '@example.com');
            $coach->setType($i % 2 === 0 ? 'Fitness' : 'Yoga');
            $coach->setBio('Bio du coach ' . $i);
            $coach->setExperiences(($i + 1) . ' ans d’expérience');
            $coach->setNationality(['FR', 'US', 'ES', 'IT', 'DE'][$i]);
            $coach->setLanguages(['FR', 'EN', 'ES', 'IT', 'DE'][$i]);
            $coach->setUser($users[$i]);
            $users[$i]->setCoach($coach);

            $manager->persist($coach);
            $coaches[] = $coach;
        }

        // --- Création des subscriptions ---
        foreach ($users as $i => $user) {
            $subscription = new Subscription();
            $subscription->setUser($user);
            $user->setSubscription($subscription);
            $subscription->setStartDate((new \DateTime())->modify('-' . rand(10, 100) . ' days'));
            $subscription->setEndDate((new \DateTime())->modify('+' . rand(30, 365) . ' days'));
            $subscription->setStatus($subscriptionStatuses[array_rand($subscriptionStatuses)]);

            $manager->persist($subscription);
        }

        // --- Création des vidéos ---
        for ($i = 0; $i < 5; $i++) {
            $video = new Videos();
            $video->setTitle('Vidéo ' . ($i + 1));
            $video->setLink('https://example.com/video' . ($i + 1));
            $video->setDescription('Description de la vidéo ' . ($i + 1));
            $video->setAccess($roles[$i]); // accès selon le rôle

            // Lier aléatoirement aux utilisateurs
            $randUsers = (array)array_rand($users, rand(1, 3));
            foreach ($randUsers as $uKey) {
                $video->addUser($users[$uKey]);
                $users[$uKey]->addVideo($video);
            }

            $manager->persist($video);
            $videos[] = $video;
        }

        // --- Création des coachings ---
        for ($i = 0; $i < 5; $i++) {
            $coaching = new Coaching();
            $coaching->setUser($users[$i]);
            $coaching->setCoach($coaches[$i]);
            $coaching->setDatetime((new \DateTime())->modify('+' . rand(1, 30) . ' days'));
            $coaching->setCoachingType(rand(0, 1) ? 'Privé' : 'Groupe');
            $coaching->setStatus($coachingStatuses[array_rand($coachingStatuses)]);

            $manager->persist($coaching);
        }

        // --- Création des feedbacks ---
        for ($i = 0; $i < 5; $i++) {
            $feedback = new Feedback();
            $feedback->setUser($users[$i]);
            $feedback->setCoach($coaches[$i]);
            $feedback->setText('Super coaching pour ' . $users[$i]->getUsername());
            $feedback->setCreationDate((new \DateTime())->modify('-' . rand(1, 50) . ' days'));

            $manager->persist($feedback);
        }

        $manager->flush();
    }
}
