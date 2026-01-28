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
        $roles = [  // par défaut visiteur
        User::ROLE_JOUEUR,    // joueur
        User::ROLE_ABONNE,    // abonné
        User::ROLE_COACH,     // coach
        User::ROLE_ADMIN      // admin
        ];
        $subscriptionStatuses = ['1 month', '6 month', '12 month'];
        $coachingStatuses = ['asked', 'confirmed', 'done'];

        $users = [];
        $coaches = [];
        $videos = [];

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $username = strtolower($firstNames[$i] . '.' . $lastNames[$i]);
            $user->setUsername($username);
            $user->setEmail($username . '@example.com');
            $user->setPassword(password_hash('Password123', PASSWORD_BCRYPT));
            $user->setRoles([$roles[$i]]);
            $user->setRegistrationDate((new \DateTime())->modify('-' . rand(0, 365) . ' days'));

            $user->setCoin(0);

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 0; $i < 5; $i++) {
            $coach = new Coach();
            $coach->setPseudo('Coach_' . $i);
            $coach->setEmail('coach' . $i . '@example.com');
            $coach->setType($i % 2 === 0 ? 'Pro' : 'Bénévole');
            $coach->setBio('lores ' . $i);
            $coach->setExperiences(($i + 1) . ' ans d’expérience');
            $coach->setNationality(['FR', 'US', 'ES', 'IT', 'DE'][$i]);
            $coach->setLanguages(['FR', 'EN', 'ES', 'IT', 'DE'][$i]);
            $coach->setUser($users[$i]);
            $users[$i]->setCoach($coach);

            $manager->persist($coach);
            $coaches[] = $coach;
        }

        foreach ($users as $i => $user) {
            $subscription = new Subscription();
            $subscription->setUser($user);
            $user->setSubscription($subscription);
            $subscription->setStartDate((new \DateTime())->modify('-' . rand(10, 100) . ' days'));
            $subscription->setEndDate((new \DateTime())->modify('+' . rand(30, 365) . ' days'));
            $subscription->setStatus($subscriptionStatuses[array_rand($subscriptionStatuses)]);

            $manager->persist($subscription);
        }

        for ($i = 0; $i < 5; $i++) {
            $video = new Videos();
            $video->setTitle('Vidéo ' . ($i + 1));
            $video->setLink('https://example.com/video' . ($i + 1));
            $video->setDescription('Description de la vidéo ' . ($i + 1));
            $video->setAccess($roles[$i]);

            $manager->persist($video);
            $videos[] = $video;
        }

        for ($i = 0; $i < 5; $i++) {
            $coaching = new Coaching();
            $coaching->setUser($users[$i]);
            $coaching->setCoach($coaches[$i]);
            $coaching->setDatetime((new \DateTime())->modify('+' . rand(1, 30) . ' days'));
            $coaching->setCoachingType(rand(0, 1) ? 'Privé' : 'Groupe');
            $coaching->setStatus($coachingStatuses[array_rand($coachingStatuses)]);

            $manager->persist($coaching);
        }

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
