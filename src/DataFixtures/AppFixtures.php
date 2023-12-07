<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $users = [];
        $articles = [];
        $comments = [];

        for ( $i = 0; $i < 15; $i++ ) {
            $user = new User();
            $user
                ->setRoles(['ROLE_USER'])
                ->setUuid('user_' . $i)
                ->setEmail('user_' . $i . '@localhost')
                ->setIsVerified(true)
                ->setPassword($this->userPasswordHasher->hashPassword($user, 'user'))
            ;
            $manager->persist($user);
            $users[] = $user;
        }

        for ( $i = 0; $i < 100; $i++ ) {
            $article = new Article();
            $article
                ->setTitle($faker->sentence(3))
                ->setContent($faker->text(1000))
                ->setCreatedAt((new \DateTimeImmutable())->setTimestamp($faker->dateTimeBetween('-1 years', 'now')->getTimestamp()))
                ->setSlug($faker->slug)
                ->setAuthor($faker->randomElement($users))
            ;

            if ( ($i%10) !== 0 ) {
                $article
                    ->setPublishedAt((new \DateTimeImmutable())->setTimestamp($faker->dateTimeBetween('-1 years', 'now')->getTimestamp()));
            }

            $manager->persist($article);
            $articles[] = $article;
        }

        for ( $i = 0; $i < 300; $i++ ) {
            $comment = new Comment();
            $comment
                ->setContent($faker->text(1000))
                ->setCreatedAt((new \DateTimeImmutable())->setTimestamp($faker->dateTimeBetween('-1 years', 'now')->getTimestamp()))
                ->setAuthor($faker->randomElement($users))
                ->setArticle($faker->randomElement($articles))
            ;

            $manager->persist($comment);
            $comments[] = $comment;
        }

        $user = new User();
        $user
            ->setRoles(['ROLE_ADMIN'])
            ->setUuid('admin')
            ->setEmail('admin@localhost')
            ->setIsVerified(true)
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'admin'))
        ;
        $manager->persist($user);

        $manager->flush();
    }
}
