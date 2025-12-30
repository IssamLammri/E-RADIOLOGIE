<?php
// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'issamlamri34000@gmail.com',
                'firstName' => 'Issam',
                'lastName' => 'LAMMRI',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'issamlammri',
            ],
            [
                'email' => 'admin@eradiologie.test',
                'firstName' => 'Douaa',
                'lastName' => 'Benali',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'Admin123!',
            ],
            [
                'email' => 'radiologue1@eradiologie.test',
                'firstName' => 'Karim',
                'lastName' => 'Mansouri',
                'roles' => ['ROLE_USER'],
                'password' => 'Radio123!',
            ],
            [
                'email' => 'radiologue2@eradiologie.test',
                'firstName' => 'Khaoula',
                'lastName' => 'CHEBIR',
                'roles' => ['ROLE_USER'],
                'password' => 'Radio123!',
            ],
            [
                'email' => 'secretaire@eradiologie.test',
                'firstName' => 'Assil',
                'lastName' => 'Cherif',
                'roles' => ['ROLE_USER'],
                'password' => 'User123!',
            ],
            [
                'email' => 'user1@eradiologie.test',
                'firstName' => 'Omer',
                'lastName' => 'LAMMRI',
                'roles' => ['ROLE_USER'],
                'password' => 'User123!',
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setRoles($data['roles']);

            // Hash du mot de passe
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
