<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\EmployeeBundle\Entity\EmployeeGroup;

/**
 * Class LoadEmployeeGroupData
 *
 * @package UmberFirm\Bundle\EmployeeBundle\DataFixtures\ORM
 */
class LoadEmployeeGroupData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $employeeGroup1 = new EmployeeGroup();
        /** @var string $locale */
        $locale = $employeeGroup1->getDefaultLocale();
        $employeeGroup1->setName('Developers', $locale);
        $employeeGroup1->mergeNewTranslations();
        $manager->persist($employeeGroup1);

        $employeeGroup2 = new EmployeeGroup();
        $employeeGroup2->setName('Content', $locale);
        $employeeGroup2->mergeNewTranslations();
        $manager->persist($employeeGroup2);

        $manager->flush();

        $this->setReference('EmployeeGroup:Developers', $employeeGroup1);
        $this->setReference('EmployeeGroup:Content', $employeeGroup2);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 12;
    }
}
