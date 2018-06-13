<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            //3rd party
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new Bazinga\Bundle\RestExtraBundle\BazingaRestExtraBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
            new Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new Hackzilla\Bundle\PasswordGeneratorBundle\HackzillaPasswordGeneratorBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),

            //UF own bundles
            new UmberFirm\Bundle\ShopBundle\UmberFirmShopBundle(),
            new UmberFirm\Bundle\CommonBundle\UmberFirmCommonBundle(),
            new UmberFirm\Bundle\ManufacturerBundle\UmberFirmManufacturerBundle(),
            new UmberFirm\Bundle\SupplierBundle\UmberFirmSupplierBundle(),
            new UmberFirm\Bundle\ProductBundle\UmberFirmProductBundle(),
            new UmberFirm\Bundle\CategoryBundle\UmberFirmCategoryBundle(),
            new UmberFirm\Bundle\MediaBundle\UmberFirmMediaBundle(),
            new UmberFirm\Bundle\CustomerBundle\UmberFirmCustomerBundle(),
            new UmberFirm\Bundle\OrderBundle\UmberFirmOrderBundle(),
            new UmberFirm\Bundle\EmployeeBundle\UmberFirmEmployeeBundle(),
            new UmberFirm\Bundle\PublicBundle\UmberFirmPublicBundle(),
            new UmberFirm\Bundle\PaymentBundle\UmberFirmPaymentBundle(),
            new UmberFirm\Bundle\SubscriptionBundle\UmberFirmSubscriptionBundle(),
            new UmberFirm\Bundle\CatalogBundle\UmberFirmCatalogBundle(),
            new UmberFirm\Bundle\DeliveryBundle\UmberFirmDeliveryBundle(),
            new UmberFirm\Bundle\NotificationBundle\UmberFirmNotificationBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
