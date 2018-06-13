<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UmberFirm\Bundle\ShopBundle\Entity\Store;

/**
 * Class LoadStoreData
 *
 * @package UmberFirm\Bundle\ShopBundle\DataFixtures\ORM
 */
class LoadStoreData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        //Ocean Plaza
        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setAddress('Киев, ул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й этаж', 'ru');
        $storeOcean->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ua'
        );
        $storeOcean->setAddress('Kyiv, Antonovycha st., 176, "Ocean Plaza", 1-й floor', 'en');
        $storeOcean->setDescription(
            'Helen Marlen Ocean — магазин демократичной мужской и женской обуви, а также аксессуаров.'.
            ' Тут представлено всё многообразие стилей — от классики до спортивных моделей — как европейских,'.
            ' так и американских брендов, в числе которых: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele,'.
            ' Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff и другие.',
            'ru'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.'.
            ' Here is presented the whole variety of styles - from classics to sports models - both European and American brands,'.
            ' including: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele, Crime London, Veja, Stokton, Colors of California,'.
            ' MOU, Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - магазин демократичною чоловічого та жіночого взуття, а також аксесуарів.'.
            ' Тут представлено все різноманіття стилів - від класики до спортивних моделей - як європейських,'.
            ' так і американських брендів, в числі яких: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele,'.
            ' Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff і інші.',
            'en'
        );
        $storeOcean->setSchedule('пн. - вc. с 10:00 до 22:00', 'ru');
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeOcean->setSchedule('monday - sunday from 10:00 till 22:00', 'en');
        $storeOcean->setIsActive(true);
        $storeOcean->mergeNewTranslations();

        //Mandarin Plaza
        $storeMandarin = new Store();
        $storeMandarin->setName('Mandarin Plaza');
        $storeMandarin->setAddress('Киев, ул. Бассейная, д.6', 'ru');
        $storeMandarin->setAddress('Київ, вул. Бассейна, б.6', 'ua');
        $storeMandarin->setAddress('Kyiv, Basseynaya str., 6', 'en');
        $storeMandarin->setDescription(
            'Mandarin Plaza – первый крупнейший торговый центр Украины TOP'.
            ' world fashion brands для королевского шопинга!',
            'ru'
        );
        $storeMandarin->setDescription(
            'Mandarin Plaza - перший найбільший торговий центр України TOP'.
            ' world fashion brands для королівського шопінгу!',
            'ua'
        );
        $storeMandarin->setDescription(
            'Mandarin Plaza - the first major shopping center in Ukraine TOP'.
            ' world fashion brands for the royal shopping!',
            'en'
        );
        $storeMandarin->setSchedule('пн. - вc. с 10:00 до 22:00', 'ru');
        $storeMandarin->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeMandarin->setSchedule('sunday - monday from 10:00 till 22:00', 'en');
        $storeMandarin->setIsActive(true);
        $storeMandarin->mergeNewTranslations();

        //Gucci
        $storeGucci = new Store();
        $storeGucci->setName('Gucci');
        $storeGucci->setAddress('Киев, Пассаж, ул. Крещатик, 15', 'ru');
        $storeGucci->setAddress('Київ, Пасаж, вул. Хрещатик, 15', 'ua');
        $storeGucci->setAddress('Kyiv, Passage, Khreshchatyk st, 15', 'en');
        $storeGucci->setDescription(
            'Официальный магазин итальянского дома Gucci в Киеве. С момента открытия'.
            ' в 2006 году в бутике представлены женская и мужская линии одежды, обувь и аксессуары.',
            'ru'
        );
        $storeGucci->setDescription(
            'Офіційний магазин італійського дому Gucci в Києві. З моменту відкриття'.
            ' в 2006 році в бутіку представлені жіноча і чоловіча лінії одягу, взуття та аксесуари.',
            'ua'
        );
        $storeGucci->setDescription(
            'The official store of the Italian house Gucci in Kiev. Since opening'.
            ' in 2006 are presented in the boutique for men and women clothing line, shoes and accessories.',
            'en'
        );
        $storeGucci->setSchedule('пн. - сб. с 11:00 до 21:00, вс. - с 11:00 до 20:00', 'ru');
        $storeGucci->setSchedule('пн. - сб. з 11:00 до 21:00, нд. - с 11:00 до 20:00', 'ua');
        $storeGucci->setSchedule(
            'saturday - monday from 11:00 till 21:00, sunday from 11:00 till 20:00',
            'en'
        );
        $storeGucci->setIsActive(true);
        $storeGucci->mergeNewTranslations();

        //Salvatore Ferragamo
        $storeSalvatoreFerragamo = new Store();
        $storeSalvatoreFerragamo->setName('Salvatore Ferragamo');
        $storeSalvatoreFerragamo->setAddress('Киев, Пассаж, ул. Крещатик, 15', 'ru');
        $storeSalvatoreFerragamo->setAddress('Київ, Пасаж, вул. Хрещатик, 15', 'ua');
        $storeSalvatoreFerragamo->setAddress('Kyiv, Passage, Khreshchatyk st, 15', 'en');
        $storeSalvatoreFerragamo->setDescription(
            'Флагманский бутик итальянского бренда с семейными традициями. С первого дня открытия'.
            ' в 2008 году магазин Salvatore Ferragamo предлагает своим клиентам женскую и мужскую одежду,'.
            ' аксессуары и главное &ndash; обувь знаменитого флорентийского дома, над которой трудяться лучшие обувщики Италии.',
            'ru'
        );
        $storeSalvatoreFerragamo->setDescription(
            'Флагманський бутик італійського бренду з сімейними традиціями. З першого дня відкриття в 2008'.
            ' році магазин Salvatore Ferragamo пропонує своїм клієнтам жіночий і чоловічий одяг, аксесуари і'.
            ' головне & ndash; взуття знаменитого флорентійського будинку, над якою трудяться кращі виробники взуття Італії.',
            'ua'
        );
        $storeSalvatoreFerragamo->setDescription(
            'Flagship boutique Italian brand with family traditions. From the first day of opening in 2008,'.
            ' Salvatore Ferragamo store offers its customers a male and female clothing, accessories, and most'.
            ' importantly & ndash; shoes of the famous Florentine house, of which the working people the best shoemakers in Italy.',
            'en'
        );
        $storeSalvatoreFerragamo->setSchedule('пн. - сб. с 11:00 до 21:00, вс. - с 11:00 до 20:00', 'ru');
        $storeSalvatoreFerragamo->setSchedule('пн. - сб. з 11:00 до 21:00, нд. - с 11:00 до 20:00', 'ua');
        $storeSalvatoreFerragamo->setSchedule(
            'saturday - monday from 11:00 till 21:00, sunday from 11:00 till 20:00',
            'en'
        );
        $storeSalvatoreFerragamo->setIsActive(true);
        $storeSalvatoreFerragamo->mergeNewTranslations();

        //HELEN-MARLEN.COM Showroom
        $storeHelenMarlenShowroom = new Store();
        $storeHelenMarlenShowroom->setName('HELEN-MARLEN.COM Showroom');
        $storeHelenMarlenShowroom->setAddress('Киев, ул. А. Тарасовой, 5, "Hyatt Regency Kyiv"', 'en');
        $storeHelenMarlenShowroom->setAddress('Kyiv, A.Tarasovoy st., 5, "Hyatt Regency Kyiv"', 'ru');
        $storeHelenMarlenShowroom->setAddress('Київ, вул. А. Тарасової, 5, "Hyatt Regency Kyiv"', 'ua');
        $storeHelenMarlenShowroom->setDescription(
            'Представитель Helen Marlen в пятизвездочном отеле Hyatt Regency Kyiv, открывшийся в 2010 году.'.
            ' Здесь собраны вместе лучшие бренды сети, в числе которых Gucci,'.
            ' Loro Piana, Burberry, Saint Laurent, Salvatore Ferragamo, Prada',
            'ru'
        );
        $storeHelenMarlenShowroom->setDescription(
            'Представник Helen Marlen в п\'ятизірковому готелі Hyatt Regency Kyiv, що відкрився в 2010 році.'.
            ' Тут зібрані разом кращі бренди мережі, в числі яких Gucci, Loro Piana,'.
            ' Burberry, Saint Laurent, Salvatore Ferragamo, Prada',
            'ua'
        );
        $storeHelenMarlenShowroom->setDescription(
            'Representative Helen Marlen five-star hotel Hyatt Regency Kyiv, which opened in 2010.'.
            ' It brought together the best online brands, including Gucci, Loro Piana, Burberry,'.
            ' Saint Laurent, Salvatore Ferragamo, Prada',
            'en'
        );
        $storeHelenMarlenShowroom->setSchedule('пн. - вc. с 10:00 до 21:00', 'ru');
        $storeHelenMarlenShowroom->setSchedule('пн. - нд. з 10:00 до 21:00', 'ua');
        $storeHelenMarlenShowroom->setSchedule('sunday - monday from 10:00 till 21:00', 'en');
        $storeHelenMarlenShowroom->setIsActive(true);
        $storeHelenMarlenShowroom->mergeNewTranslations();

        //POSH.UA Warehouse
        $storePoshuaWarehouse = new Store();
        $storePoshuaWarehouse->setName('POSH.UA Warehouse');
        $storePoshuaWarehouse->setAddress('Kyiv, Kharkivske shose, 201/203', 'en');
        $storePoshuaWarehouse->setAddress('Киев, ул. Харьковское шоссе, 201/203', 'ru');
        $storePoshuaWarehouse->setAddress('Київ, вул. Харківське шосе, 201/203', 'ua');
        $storePoshuaWarehouse->setDescription(
            'POSH.ua объединяет на одной платформе ассортимент всех'.
            ' мультибрендовых и монобрендовых магазинов Украины.',
            'ru'
        );
        $storePoshuaWarehouse->setDescription(
            'POSH.ua об\'єднує на одній платформі асортимент всіх'.
            ' мультибрендових і монобрендових магазинів України.',
            'ua'
        );
        $storePoshuaWarehouse->setDescription(
            'POSH.ua brings together on one platform all the range of'.
            ' multi-brand and single-brand stores in Ukraine.',
            'en'
        );
        $storePoshuaWarehouse->setSchedule('пн. - вc. с 10:00 до 21:00', 'ru');
        $storePoshuaWarehouse->setSchedule('пн. - нд. з 10:00 до 21:00', 'ua');
        $storePoshuaWarehouse->setSchedule('sunday - monday from 10:00 till 21:00', 'en');
        $storePoshuaWarehouse->setIsActive(true);
        $storePoshuaWarehouse->mergeNewTranslations();

        $manager->persist($storeOcean);
        $manager->persist($storeMandarin);
        $manager->persist($storeGucci);
        $manager->persist($storeSalvatoreFerragamo);
        $manager->persist($storeHelenMarlenShowroom);
        $manager->persist($storePoshuaWarehouse);

        $manager->flush();

        $this->addReference('STORE_Ocean', $storeOcean);
        $this->addReference('STORE_Mandarin', $storeMandarin);
        $this->addReference('STORE_Gucci', $storeGucci);
        $this->addReference('STORE_SalvatoreFerragamo', $storeSalvatoreFerragamo);
        $this->addReference('STORE_HelenMarlenShowroom', $storeHelenMarlenShowroom);
        $this->addReference('STORE_StorePoshuaWarehouse', $storePoshuaWarehouse);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
