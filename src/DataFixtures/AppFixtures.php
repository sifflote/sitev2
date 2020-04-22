<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
private $passwordEncoder;

public function __construct(UserPasswordEncoderInterface $passwordEncoder)
{
$this->passwordEncoder = $passwordEncoder;
}

public function load(ObjectManager $manager)
{
foreach ($this->getUserData() as [$username, $password, $email, $roles]) {
$user = new User();
$user->setUsername($username);
$user->setPassword($this->passwordEncoder->encodePassword($user, $password));
$user->setEmail($email);
$user->setRoles($roles);

$manager->persist($user);
$this->addReference($username, $user);
}

$manager->flush();
}

private function getUserData(): array
{
return [
// $userData = [$username, $password, $email, $roles];
['admin', 'admin', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
['demo', 'demo', 'john_user@symfony.com', ['ROLE_USER']],
];
}


}