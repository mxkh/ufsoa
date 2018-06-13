<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;

/**
 * Class UmberFirmImportAttributes
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmImportAttributesCommand extends ContainerAwareCommand
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:import:attributes')
            ->setDescription('Command for importing supplier products.')
            ->addArgument('domain', InputArgument::OPTIONAL, 'domain for parsing', 'https://posh.ua/');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->domain = $input->getArgument('domain');
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->locale = $this->getContainer()->getParameter('locale');
        $this->client = new Client();
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $q = $this->entityManager->createQuery('SELECT s FROM '.Supplier::class.' s');
        $count = $this->entityManager
            ->createQuery('SELECT count(s) FROM '.Supplier::class.' s')
            ->getSingleScalarResult();

        $progress = new ProgressBar($output, $count);
        $progress->start();

        $iterable = $q->iterate();
        foreach ($iterable as $row) {
            $progress->advance();
            /** @var Supplier $supplier */
            $supplier = $row['0'];

            $this->createManufacturers($supplier);
            $this->createStores($supplier);
            $this->createSizesAttributes($supplier);
            $this->createColorAttributes($supplier);
//            $this->createGenderFeatures($supplier);

            $this->entityManager->flush();
            $this->entityManager->clear();
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
        $progress->finish();
    }

    /**
     * @param Supplier $supplier
     */
    private function createManufacturers(Supplier $supplier)
    {
        $response = $this->client->request('GET', $this->domain.'import/brands.php');
        $items = $this->parseXML($response->getBody()->getContents());
        $repository = $this->entityManager->getRepository(Manufacturer::class);

        $progress = new ProgressBar($this->output, count($items['row']));
        $progress->start();

        foreach ($items['row'] as $key => $item) {
            if (true === is_array($item['name']) || true === is_array($item['id'])) {
                continue;
            }
            $progress->advance();

            $manufacturer = $repository->findOneBy(['name' => $item['name']]);
            if (null !== $manufacturer) {
                $manufacturers[$key] = $manufacturer;
            } else {
                /** @var array|Manufacturer[] $manufacturers */
                $manufacturers[$key] = new Manufacturer();
                $manufacturers[$key]->setName((string) $item['name']);
                $manufacturers[$key]->setWebsite((string) $item['name'].'.com');
                $manufacturers[$key]->mergeNewTranslations();
            }


            /** @var array|SupplierManufacturerMapping[] $manufacturerMappings */
            $manufacturerMappings[$key] = new SupplierManufacturerMapping();
            $manufacturerMappings[$key]->setSupplier($supplier);
            $manufacturerMappings[$key]->setManufacturer($manufacturers[$key]);
            $manufacturerMappings[$key]->setSupplierManufacturer((string) $item['id']);

            $this->entityManager->persist($manufacturers[$key]);
            $this->entityManager->persist($manufacturerMappings[$key]);
        }

        $progress->finish();
    }

    /**
     * @param Supplier $supplier
     */
    private function createStores(Supplier $supplier)
    {
        $response = $this->client->request('GET', $this->domain.'import/stores.php');
        $items = $this->parseXML($response->getBody()->getContents());
        $repository = $this->entityManager->getRepository(Store::class);

        $progress = new ProgressBar($this->output, count($items['row']));
        $progress->start();

        foreach ($items['row'] as $key => $item) {
            if (true === is_array($item['name']) || true === is_array($item['id'])) {
                continue;
            }
            $progress->advance();
            /** @var array|Store[] $stores */

            $store = $repository->findOneBy(['name' => $item['name']]);
            if (null !== $store) {
                $stores[$key] = $store;
            } else {
                $stores[$key] = new Store();
                $stores[$key]->setName((string) $item['name']);
            }

            /** @var array|SupplierStoreMapping[] $storeMappings */
            $storeMappings[$key] = new SupplierStoreMapping();
            $storeMappings[$key]->setSupplier($supplier);
            $storeMappings[$key]->setStore($stores[$key]);
            $storeMappings[$key]->setSupplierStore((string) $item['id']);

            $this->entityManager->persist($stores[$key]);
            $this->entityManager->persist($storeMappings[$key]);
        }
        $progress->finish();
    }

    /**
     * @param Supplier $supplier
     */
    private function createSizesAttributes(Supplier $supplier)
    {
        $attributeGroup = $this->createAttributeGroup('Размер', 'select');
        $repository = $this->entityManager->getRepository(Attribute::class);

        $response = $this->client->request('GET', $this->domain.'import/sizes.php');
        $items = $this->parseXML($response->getBody()->getContents());

        $progress = new ProgressBar($this->output, count($items['row']));
        $progress->start();

        foreach ($items['row'] as $key => $item) {
            if (true === is_array($item['name']) || true === is_array($item['id'])) {
                continue;
            }

            $attribute = $repository->findOneBy(['code' => $item['name']]);
            if (null !== $attribute) {
                $attributes[$key] = $attribute;
            } else {
                /** @var array|Attribute[] $attributes */
                $attributes[$key] = new Attribute();
                $attributes[$key]->setName($item['name'], $this->locale);
                $attributes[$key]->setAttributeGroup($attributeGroup);
                $attributes[$key]->mergeNewTranslations();
            }
            $progress->advance();
            /** @var array|SupplierAttributeMapping[] $attributeMappings */
            $attributeMappings[$key] = new SupplierAttributeMapping();
            $attributeMappings[$key]->setSupplier($supplier);
            $attributeMappings[$key]->setAttribute($attributes[$key]);
            $attributeMappings[$key]->setSupplierAttributeKey('size');
            $attributeMappings[$key]->setSupplierAttributeValue((string) $item['id']);

            $this->entityManager->persist($attributes[$key]);
            $this->entityManager->persist($attributeMappings[$key]);
        }
        $progress->finish();
    }

    /**
     * @param Supplier $supplier
     */
    private function createGenderFeatures(Supplier $supplier)
    {
        $feature = new Feature();
        $feature->setName('Gender', $this->locale);
        $feature->mergeNewTranslations();

        $women = new FeatureValue();
        $women->setFeature($feature);
        $women->setValue('Women', $this->locale);
        $women->mergeNewTranslations();

        $men = new FeatureValue();
        $men->setFeature($feature);
        $men->setValue('Men', $this->locale);
        $men->mergeNewTranslations();

        $featureMenMappings = new SupplierFeatureMapping();
        $featureMenMappings->setFeatureValue($men);
        $featureMenMappings->setSupplier($supplier);
        $featureMenMappings->setSupplierFeatureKey('gender');
        $featureMenMappings->setSupplierFeatureValue('m');

        $featureWomenMappings = new SupplierFeatureMapping();
        $featureWomenMappings->setFeatureValue($women);
        $featureWomenMappings->setSupplier($supplier);
        $featureWomenMappings->setSupplierFeatureKey('gender');
        $featureWomenMappings->setSupplierFeatureValue('f');

        $this->entityManager->persist($feature);
        $this->entityManager->persist($women);
        $this->entityManager->persist($men);
        $this->entityManager->persist($featureMenMappings);
        $this->entityManager->persist($featureWomenMappings);
    }

    /**
     * @param Supplier $supplier
     */
    private function createColorAttributes(Supplier $supplier)
    {
        $attributeGroup = $this->createAttributeGroup('Цвет', 'color', true);
        $repository = $this->entityManager->getRepository(Attribute::class);

        $response = $this->client->request('GET', $this->domain.'import/colors.php');
        $items = $this->parseXML($response->getBody()->getContents());

        $progress = new ProgressBar($this->output, count($items['row']));
        $progress->start();

        foreach ($items['row'] as $key => $item) {
            if (true === is_array($item['name']) || true === is_array($item['id'])) {
                continue;
            }

            $attribute = $repository->findOneBy(['code' => $item['name']]);
            if (null !== $attribute) {
                $attributes[$key] = $attribute;
            } else {
                /** @var array|Attribute[] $attributes */
                $attributes[$key] = new Attribute();
                $attributes[$key]->setName($item['name'], $this->locale);
                $attributes[$key]->setAttributeGroup($attributeGroup);
                $attributes[$key]->mergeNewTranslations();
            }
            $progress->advance();
            /** @var array|SupplierAttributeMapping[] $attributeMappings */
            $attributeMappings[$key] = new SupplierAttributeMapping();
            $attributeMappings[$key]->setSupplier($supplier);
            $attributeMappings[$key]->setAttribute($attributes[$key]);
            $attributeMappings[$key]->setSupplierAttributeKey('color');
            $attributeMappings[$key]->setSupplierAttributeValue((string) $item['id']);

            $this->entityManager->persist($attributes[$key]);
            $this->entityManager->persist($attributeMappings[$key]);
        }
        $progress->finish();
    }

    /**
     * Create supplied entities
     *
     * @param string $groupEnumName
     * @param string $groupName
     * @param bool $isColor
     *
     * @return AttributeGroup
     */
    private function createAttributeGroup(
        string $groupName,
        string $groupEnumName,
        bool $isColor = false
    ): AttributeGroup {
        $attributeGroupEnum = new AttributeGroupEnum();
        $attributeGroupEnum->setName($groupEnumName);

        $attributeGroup = new AttributeGroup();
        $attributeGroup->setName($groupName, $this->locale);
        $attributeGroup->setPublicName($groupName, $this->locale);
        $attributeGroup->setAttributeGroupEnum($attributeGroupEnum);
        $attributeGroup->setIsColorGroup($isColor);
        $attributeGroup->mergeNewTranslations();

        $this->entityManager->persist($attributeGroupEnum);
        $this->entityManager->persist($attributeGroup);

        return $attributeGroup;
    }

    /**
     * @param string $content
     *
     * @return array
     */
    private function parseXML(string $content): array
    {
        $xml = new \SimpleXMLElement($content);

        return (array) json_decode(json_encode($xml), true);
    }
}
