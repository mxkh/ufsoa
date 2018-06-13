<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\CommonBundle\Entity\Branch;

/**
 * Class LoadBranchData
 *
 * @package UmberFirm\Bundle\CommonBundle\DataFixtures\ORM
 */
class LoadBranchData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $branch1 = new Branch();
        $branch1->setName('Отделение в Киеве #1');
        $branch1->setRef('12adsad-asda-d2-d-d2-32-23r');
        $branch1->setNumber(1);
        $branch1->setCity($this->getReference('City:Kyiv'));
        $manager->persist($branch1);

        $branch2 = new Branch();
        $branch2->setName('Отделение в Одессе #24');
        $branch2->setRef('d12d32d-d23d2dd23g3h3h-g46j5j-g4h65');
        $branch2->setNumber(24);
        $branch2->setCity($this->getReference('City:Odesa'));
        $manager->persist($branch2);

        $manager->flush();

        $this->setReference('City:Branch1', $branch1);
        $this->setReference('City:Branch2', $branch2);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
