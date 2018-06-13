<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class LoadSupplierData
 *
 * @package UmberFirm\Bundle\SupplierBundle\DataFixtures\ORM
 */
class LoadSupplierData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $iconSupplier = new Supplier();
        $iconSupplier->setName('icon', 'ua');
        $iconSupplier->setDescription('description icon', 'ua');
        $iconSupplier->setIsActive(true);
        $iconSupplier->setUsername('supplier');
        $iconSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $iconSupplier->addShop($this->getReference('HM SHOP'));
        $iconSupplier->addShop($this->getReference('POSH SHOP'));
        $iconSupplier->addStore($this->getReference('STORE_Ocean'));
        $iconSupplier->addStore($this->getReference('STORE_Mandarin'));
        $iconSupplier->mergeNewTranslations();

        $flowSupplier = new Supplier();
        $flowSupplier->setName('flow', 'ua');
        $flowSupplier->setDescription('description flow', 'ua');
        $flowSupplier->setIsActive(true);
        $flowSupplier->setUsername('supplier2');
        $flowSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $flowSupplier->addShop($this->getReference('HM SHOP'));
        $flowSupplier->addShop($this->getReference('POSH SHOP'));
        $flowSupplier->addStore($this->getReference('STORE_Ocean'));
        $flowSupplier->addStore($this->getReference('STORE_Mandarin'));
        $flowSupplier->mergeNewTranslations();

        $omeliaSupplier = new Supplier();
        $omeliaSupplier->setName('Omelia', 'ua');
        $omeliaSupplier->setDescription('description Omelia', 'ua');
        $omeliaSupplier->setIsActive(true);
        $omeliaSupplier->setUsername('supplier3');
        $omeliaSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $omeliaSupplier->addShop($this->getReference('HM SHOP'));
        $omeliaSupplier->addShop($this->getReference('POSH SHOP'));
        $omeliaSupplier->addShop($this->getReference('MD SHOP'));
        $omeliaSupplier->addStore($this->getReference('STORE_Gucci'));
        $omeliaSupplier->mergeNewTranslations();

        $bohemianRosesSupplier = new Supplier();
        $bohemianRosesSupplier->setName('Bohemian Roses', 'ua');
        $bohemianRosesSupplier->setDescription('description Bohemian Roses', 'ua');
        $bohemianRosesSupplier->setIsActive(true);
        $bohemianRosesSupplier->setUsername('supplier4');
        $bohemianRosesSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $bohemianRosesSupplier->addShop($this->getReference('HM SHOP'));
        $bohemianRosesSupplier->addShop($this->getReference('MD SHOP'));
        $bohemianRosesSupplier->addStore($this->getReference('STORE_Ocean'));
        $bohemianRosesSupplier->addStore($this->getReference('STORE_Mandarin'));
        $bohemianRosesSupplier->mergeNewTranslations();

        $weekendSupplier = new Supplier();
        $weekendSupplier->setName('weekend', 'ua');
        $weekendSupplier->setDescription('description weekend', 'ua');
        $weekendSupplier->setIsActive(false);
        $weekendSupplier->setUsername('supplier5');
        $weekendSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $weekendSupplier->addShop($this->getReference('POSH SHOP'));
        $weekendSupplier->addShop($this->getReference('MD SHOP'));
        $weekendSupplier->addStore($this->getReference('STORE_SalvatoreFerragamo'));
        $weekendSupplier->addStore($this->getReference('STORE_Mandarin'));
        $weekendSupplier->mergeNewTranslations();

        $bevzaSupplier = new Supplier();
        $bevzaSupplier->setName('Bevza', 'ua');
        $bevzaSupplier->setDescription('description Bevza', 'ua');
        $bevzaSupplier->setIsActive(false);
        $bevzaSupplier->setUsername('supplier6');
        $bevzaSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $bevzaSupplier->addShop($this->getReference('HM SHOP'));
        $bevzaSupplier->addShop($this->getReference('MD SHOP'));
        $bevzaSupplier->addShop($this->getReference('POSH SHOP'));
        $bevzaSupplier->addStore($this->getReference('STORE_HelenMarlenShowroom'));
        $bevzaSupplier->mergeNewTranslations();

        $bekhSupplier = new Supplier();
        $bekhSupplier->setName('Bekh', 'ua');
        $bekhSupplier->setDescription('description Bekh', 'ua');
        $bekhSupplier->setIsActive(true);
        $bekhSupplier->setUsername('supplier7');
        $bekhSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $bekhSupplier->addShop($this->getReference('MD SHOP'));
        $bekhSupplier->addStore($this->getReference('STORE_StorePoshuaWarehouse'));
        $bekhSupplier->addStore($this->getReference('STORE_Mandarin'));
        $bekhSupplier->mergeNewTranslations();

        $oscarSupplier = new Supplier();
        $oscarSupplier->setName('oscar', 'ua');
        $oscarSupplier->setDescription('description oscar', 'ua');
        $oscarSupplier->setIsActive(true);
        $oscarSupplier->setUsername('supplier8');
        $oscarSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $oscarSupplier->addShop($this->getReference('MD SHOP'));
        $oscarSupplier->addShop($this->getReference('HM SHOP'));
        $oscarSupplier->addStore($this->getReference('STORE_SalvatoreFerragamo'));
        $oscarSupplier->addStore($this->getReference('STORE_Gucci'));
        $oscarSupplier->mergeNewTranslations();

        $frolovSupplier = new Supplier();
        $frolovSupplier->setName('FROLOV', 'ua');
        $frolovSupplier->setDescription('description FROLOV', 'ua');
        $frolovSupplier->setIsActive(true);
        $frolovSupplier->setUsername('supplier9');
        $frolovSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $frolovSupplier->addShop($this->getReference('POSH SHOP'));
        $frolovSupplier->addShop($this->getReference('HM SHOP'));
        $frolovSupplier->addStore($this->getReference('STORE_StorePoshuaWarehouse'));
        $frolovSupplier->addStore($this->getReference('STORE_Mandarin'));
        $frolovSupplier->mergeNewTranslations();

        $cornerSupplier = new Supplier();
        $cornerSupplier->setName('Corner', 'ua');
        $cornerSupplier->setDescription('description Corner', 'ua');
        $cornerSupplier->setIsActive(true);
        $cornerSupplier->setUsername('supplier10');
        $cornerSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $cornerSupplier->addShop($this->getReference('MD SHOP'));
        $cornerSupplier->addStore($this->getReference('STORE_SalvatoreFerragamo'));
        $cornerSupplier->addStore($this->getReference('STORE_HelenMarlenShowroom'));
        $cornerSupplier->mergeNewTranslations();

        $coverSupplier = new Supplier();
        $coverSupplier->setName('COVER', 'ua');
        $coverSupplier->setDescription('description COVER', 'ua');
        $coverSupplier->setIsActive(true);
        $coverSupplier->setUsername('supplier11');
        $coverSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $coverSupplier->addShop($this->getReference('POSH SHOP'));
        $coverSupplier->addStore($this->getReference('STORE_Ocean'));
        $coverSupplier->addStore($this->getReference('STORE_Mandarin'));
        $coverSupplier->mergeNewTranslations();

        $manager->persist($iconSupplier);
        $manager->persist($flowSupplier);
        $manager->persist($omeliaSupplier);
        $manager->persist($bohemianRosesSupplier);
        $manager->persist($weekendSupplier);
        $manager->persist($bevzaSupplier);
        $manager->persist($bekhSupplier);
        $manager->persist($oscarSupplier);
        $manager->persist($frolovSupplier);
        $manager->persist($cornerSupplier);
        $manager->persist($coverSupplier);

        $manager->flush();

        $this->addReference('icon_supplier', $iconSupplier);
        $this->addReference('flow_supplier', $flowSupplier);
        $this->addReference('omelia_supplier', $omeliaSupplier);
        $this->addReference('bohemian_roses_supplier', $bohemianRosesSupplier);
        $this->addReference('weekend_supplier', $weekendSupplier);
        $this->addReference('bevza_supplier', $bevzaSupplier);
        $this->addReference('bekh_supplier', $bekhSupplier);
        $this->addReference('oscar_supplier', $oscarSupplier);
        $this->addReference('frolov_supplier', $frolovSupplier);
        $this->addReference('corner_supplier', $cornerSupplier);
        $this->addReference('cover_supplier', $coverSupplier);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
