<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;

/**
 * Class LoadEmployeeData
 *
 * @package UmberFirm\Bundle\EmployeeBundle\DataFixtures\ORM
 */
class LoadEmployeeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $employee = new Employee();
        $employee->setName('Kolya');
        $employee->setBirthday(new \DateTime);
        $employee->setEmail('test@gmail.com');
        $employee->setPhone('380951234567');
        $employee->setPassword(password_hash('qwerty', PASSWORD_BCRYPT));
        $employee->setEmployeeGroup($this->getReference('EmployeeGroup:Developers'));
        $manager->persist($employee);

        $employee2 = new Employee();
        $employee2->setName('Petya');
        $employee2->setBirthday(new \DateTime);
        $employee2->setEmail('test2@gmail.com');
        $employee2->setPhone('380951234566');
        $employee2->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $employee2->setEmployeeGroup($this->getReference('EmployeeGroup:Developers'));
        $manager->persist($employee2);

        $employee3 = new Employee();
        $employee3->setName('Vasya');
        $employee3->setBirthday(new \DateTime);
        $employee3->setEmail('test2@gmail.com');
        $employee3->setPhone('380951234565');
        $employee3->setPassword(password_hash('123456', PASSWORD_BCRYPT));
        $employee3->setEmployeeGroup($this->getReference('EmployeeGroup:Content'));
        $manager->persist($employee3);

        $manager->flush();

        $this->setReference('Employee:Kolya', $employee);
        $this->setReference('Employee:Petya', $employee2);
        $this->setReference('Employee:Vasya', $employee3);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 13;
    }
}
