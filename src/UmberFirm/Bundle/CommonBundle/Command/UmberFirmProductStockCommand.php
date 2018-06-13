<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\SupplierBundle\Services\Import\ImportProduct;
use UmberFirm\Component\Elastica\Event\SubscriberTrait;

/**
 * Class UmberFirmUpdateProductStocks
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmProductStockCommand extends ContainerAwareCommand
{
    use SubscriberTrait;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:product:stock')
            ->setDescription('Command for update product stocks');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->input = $input;
        $this->output = $output;

        $importListener = $this->getContainer()->get('umber_firm_supplier.event.event_listener.import');
        $this->entityManager->getEventManager()->removeEventListener(Events::postFlush, $importListener);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);

        $this->updateProductVariantStock();
        $this->updateProductStock();

        $time_elapsed_secs = microtime(true) - $start;
        $output->writeln('Executing import time '.($time_elapsed_secs / 60));
    }

    /**
     * @param void
     */
    private function updateProductVariantStock(): void
    {
        $start = microtime(true);

        $variantRepository = $this->entityManager->getRepository(ProductVariant::class);
        $count = $variantRepository->count([]);
        $iterable = $variantRepository->createQueryBuilder('pv')->getQuery()->iterate();
        $i = 1;
        $updated = 0;

        $progress = new ProgressBar($this->output, $count);
        $progress->start();

        /**
         * @var array|ProductVariant[] $row
         */
        foreach ($iterable as $row) {
            /** @var ProductVariant $productVariant */
            $productVariant = $row['0'];
            $updated += $variantRepository->updateStock($productVariant);

            if (($i % ImportProduct::BATCH_SIZE) === 0) {
                $this->entityManager->clear();
            }

            $progress->advance();
            $i++;
        }
        $this->entityManager->clear();
        $progress->finish();

        $time_elapsed_secs = microtime(true) - $start;
        $this->output->writeln('Executing updateProductVariantStock time '.($time_elapsed_secs / 60));
    }

    /**
     * @param void
     */
    private function updateProductStock(): void
    {
        $start = microtime(true);

        $productRepository = $this->entityManager->getRepository(Product::class);
        $count = $productRepository->count([]);
        $iterable = $productRepository->createQueryBuilder('p')->getQuery()->iterate();
        $i = 1;
        $updated = 0;

        $progress = new ProgressBar($this->output, $count);
        $progress->start();

        /**
         * @var array|Product[] $row
         */
        foreach ($iterable as $row) {
            /** @var Product $product */
            $product = $row['0'];
            $updated += $productRepository->updateStock($product);

            if (($i % ImportProduct::BATCH_SIZE) === 0) {
                $this->entityManager->clear();
            }
            $i++;
            $progress->advance();
        }
        $this->entityManager->clear();
        $progress->finish();

        $time_elapsed_secs = microtime(true) - $start;
        $this->output->writeln('Executing updateProductStock time '.($time_elapsed_secs / 60));
    }
}
