<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command\NovaPoshta;

use Doctrine\Common\Persistence\ObjectManager;
use LisDev\Delivery\NovaPoshtaApi2;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UmberFirm\Bundle\CommonBundle\Entity\Branch;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Street;

/**
 * Class UmberFirmNovaPoshtaImportCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command\NovaPoshta
 */
class UmberFirmNovaPoshtaImportCommand extends ContainerAwareCommand
{
    /**
     * @var NovaPoshtaApi2
     */
    protected $api;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->api = $this->getApi();
        $this->manager = $this->getContainer()->get('doctrine')->getManager();
        $this->output = $output;

        parent::initialize($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:novaposhta:import')
            ->setDescription('Help to import cities, streets and warehouses from nova poshta.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importCities();
        $this->importStreets();
        $this->importBranches();
    }

    /**
     * @param int $page
     *
     * @return void
     */
    protected function importCities(int $page = 1): void
    {
        $cities = (array) $this->api->getCities($page);
        $count = count($cities['data']);

        if ($count === 0) {
            $this->output->write(PHP_EOL.'Import of cities a finished.', true);

            return;
        }

        $progress = new ProgressBar($this->output, $count);
        $progress->start();

        $repository = $this->getContainer()->get('doctrine')->getRepository(City::class);

        for ($i = 0, $size = (int) count($cities['data']); $i < $size; ++$i) {
            $city = $repository->findOneBy(['ref' => $cities['data'][$i]['Ref']]);

            if (null !== $city) {
                continue;
            }

            $city = new City();
            $city->setName($cities['data'][$i]['DescriptionRu']);
            $city->setRef($cities['data'][$i]['Ref']);
            $this->manager->persist($city);

            $city = null;

            $progress->advance();

            if (($i % 1000) === 0) {
                $this->manager->flush();
                $this->manager->clear(City::class);
            }
        }

        $this->manager->flush();
        $this->manager->clear();

        $progress->finish();

        $this->importCities($page + 1);
    }

    /**
     * @return void
     */
    protected function importStreets(): void
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $q = $em->createQuery('select count(1) from '.City::class.' c');
        $cities = $q->getSingleScalarResult();

        $progress = new ProgressBar($this->output, $cities);
        $progress->start();

        $q = $em->createQuery('select c from '.City::class.' c');
        $iterable = $q->iterate();

        foreach ($iterable as $row) {
            /** @var City $city */
            $city = $row['0'];
            $this->output->write(PHP_EOL.$city->getName().' streets', true);
            $this->fetchStreets($city);
            $progress->advance();
            $this->output->write(PHP_EOL.str_repeat('+', 50), true);
        }

        $progress->finish();

        $this->manager->clear();

        $this->output->write(PHP_EOL.'Import of streets a finished.', true);
    }

    /**
     * @param City $city
     * @param int $page
     *
     * @return void
     */
    protected function fetchStreets(City $city, int $page = 1): void
    {
        $streets = (array) $this->getApi()->getStreet($city->getRef(), '', $page);
        $count = count($streets['data']);

        if ($count === 0) {
            return;
        }

        $repository = $this->getContainer()->get('doctrine')->getRepository(Street::class);

        $progress = new ProgressBar($this->output, $count);
        $progress->start();

        for ($i = 0, $size = (int) count($streets['data']); $i < $size; ++$i) {
            $street = $repository->findOneBy(['ref' => $streets['data'][$i]['Ref']]);

            if (null !== $street) {
                continue;
            }

            $street = new Street();
            $street->setName($streets['data'][$i]['DescriptionRu']??$streets['data'][$i]['Description']);
            $street->setType($streets['data'][$i]['StreetsTypeRu']??$streets['data'][$i]['StreetsType']);
            $street->setRef($streets['data'][$i]['Ref']);
            $street->setCity($city);
            $this->manager->persist($street);

            $progress->advance();

            if (($i % 500) === 0) {
                $this->manager->flush();
                $this->manager->clear(Street::class);
            }
        }

        $this->manager->flush();
        $this->manager->clear(Street::class);

        $progress->finish();

        $this->fetchStreets($city, $page + 1);
    }

    /**
     * @return void
     */
    protected function importBranches(): void
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $q = $em->createQuery('select count(1) from '.City::class.' c');
        $cities = $q->getSingleScalarResult();

        $progress = new ProgressBar($this->output, $cities);
        $progress->start();

        $q = $em->createQuery('select c from '.City::class.' c');
        $iterable = $q->iterate();

        foreach ($iterable as $row) {
            /** @var City $city */
            $city = $row['0'];
            $this->output->write(PHP_EOL.$city->getName().' branches', true);
            $this->fetchBranches($city);
            $progress->advance();
            $this->output->write(PHP_EOL.str_repeat('+', 50), true);
        }

        $progress->finish();

        $this->manager->clear();

        $this->output->write(PHP_EOL.'Import of branches a finished.', true);
    }

    /**
     * @param City $city
     * @param int $page
     *
     * @return void
     */
    protected function fetchBranches(City $city, int $page = 1): void
    {
        $branches = (array) $this->getApi()->getWarehouses($city->getRef(), $page);
        $count = count($branches['data']);

        if ($count === 0) {
            return;
        }

        $repository = $this->getContainer()->get('doctrine')->getRepository(Branch::class);

        $progress = new ProgressBar($this->output, $count);
        $progress->start();

        for ($i = 0, $size = (int) count($branches['data']); $i < $size; ++$i) {
            $branch = $repository->findOneBy(['ref' => $branches['data'][$i]['Ref']]);

            if (null !== $branch) {
                continue;
            }

            $branch = new Branch();
            $branch->setName($branches['data'][$i]['DescriptionRu']??$branches['data'][$i]['Description']);
            $branch->setNumber((int) $branches['data'][$i]['Number']);
            $branch->setRef($branches['data'][$i]['Ref']);
            $branch->setCity($city);
            $this->manager->persist($branch);

            $progress->advance();

            if (($i % 10) === 0) {
                $this->manager->flush();
                $this->manager->clear(Branch::class);
            }
        }

        $this->manager->flush();
        $this->manager->clear(Branch::class);

        $progress->finish();

        $this->fetchBranches($city, $page + 1);
    }

    /**
     * @return NovaPoshtaApi2
     * @throws \Exception
     */
    protected function getApi(): NovaPoshtaApi2
    {
        $isApiURL = $this->getContainer()->hasParameter('nova_poshta_api_url');
        $isApiKey = $this->getContainer()->hasParameter('nova_poshta_api_key');

        if (false === $isApiURL || false === $isApiKey) {
            throw new \Exception('Nova poshta api_key or api_url undefined.');
        }

        $api = new NovaPoshtaApi2($this->getContainer()->getParameter('nova_poshta_api_key'));
        $api->setApiPath($this->getContainer()->getParameter('nova_poshta_api_url'));

        return $api;
    }
}
