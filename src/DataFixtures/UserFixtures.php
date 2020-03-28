<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setPassword($this->encodePassword($user, 'bananas'));
        $manager->persist($user);

        $contact = new Contact();
        $contact->setFirstName('FirstName');
        $contact->setLastName('Surname');
        $contact->setEmail('email@email.com');
        $contact->setBirthday(new \DateTime('1992-12-12'));
        $contact->setUser($user);
        $manager->persist($contact);

        $manager->flush();
    }

    /**
     * @param User $user
     * @param string $password
     * @return string
     */
    protected function encodePassword(User $user, string $password): string
    {
        $encodedPassword = $this->passwordEncoder->encodePassword(
            $user,
            $password
        );

        return $encodedPassword;
    }
}
