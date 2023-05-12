<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;

class LoadFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {


    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
      $this->container = $container;
    }

    // ...
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('superadmin');
        $user->setEmail('superadmin@svie.com');


        $encoder = $this->container->get('security.password_encoder');
        $encodedPass = $encoder->encodePassword($user, '123456789');
        $user->setPassword($encodedPass);

        $user->setEnabled(true);

        $roles = $user->getRoles();
        foreach ($roles as $role) {
          $user->removeRole($role);
        }
        $user->addRole('ROLE_ADMIN');

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}
