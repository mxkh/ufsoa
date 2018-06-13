<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;

/**
 * Class UmberFirmSluggableAttribute
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmAttributeCodeFillCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:attribute:code:fill')
            ->setDescription('Help to fill attribute codes from translation names. For local use only!');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $repository = $this->getContainer()->get('doctrine')->getRepository(Attribute::class);
        /** @var Attribute[] $collection */
        $collection = $repository->findAll();


        $progress = new ProgressBar($output, count($collection));
        $progress->start();

        foreach ($collection as $attribute) {
            $oldName = $attribute->getName();

            // Update attribute with new name for future update to old name with slug
            $attribute->setName('new name', 'ru');
            $manager->persist($attribute);
            $manager->flush();

            $attribute->setName($oldName, 'ru');
            $manager->persist($attribute);
            $manager->flush();

            $progress->advance();
        }

        $progress->finish();

        $manager->flush();
    }
}
