<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;

/**
 * Class LoadAttributeGroupEnumData
 *
 * @package UmberFirm\Bundle\ProductBundle\DataFixtures\ORM
 */
class LoadAttributeGroupEnumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $color = new AttributeGroupEnum();
        $color->setName('color');
        $manager->persist($color);

        $select = new AttributeGroupEnum();
        $select->setName('select');
        $manager->persist($select);

        $radio = new AttributeGroupEnum();
        $radio->setName('radio');
        $manager->persist($radio);

        $manager->flush();

        $this->addReference('AttributeGroupEnum:Color', $color);
        $this->addReference('AttributeGroupEnum:Select', $select);
        $this->addReference('AttributeGroupEnum:Radio', $radio);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 11;
    }
}
