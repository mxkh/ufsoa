<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UmberFirmSlugGenerationCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmSlugGenerationCommand extends ContainerAwareCommand
{
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
            ->setName('umberfirm:slug:generation')
            ->setDescription('Flushing favorites')
            ->setDefinition(
                [
                    new InputArgument(
                        'entity',
                        InputArgument::REQUIRED,
                        'The entity class name to initialize (shortcut notation)'
                    ),
                    new InputOption(
                        'batchSize',
                        null,
                        InputOption::VALUE_OPTIONAL,
                        null,
                        500
                    ),
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        parent::initialize($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);
        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle).'\\'.$entity;
        $this->regenerate($entityClass);
    }

    /**
     * @param string $entityClass
     */
    protected function regenerate(string $entityClass): void
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $q = $em->createQuery('select count(1) from '.$entityClass.' e');
        $objects = $q->getSingleScalarResult();

        $i = 1;
        $progress = new ProgressBar($this->output, $objects);
        $progress->start();

        $q = $em->createQuery('select e from '.$entityClass.' e');
        $iterable = $q->iterate();

        foreach ($iterable as $row) {
            $object = $row['0'];
            $object->setSlug(null);
            $em->persist($object);

            if (($i % $this->input->getOption('batchSize')) === 0) {
                $em->flush();
                $em->clear();
            }
            $i++;

            $progress->advance();
        }

        $em->flush();
        $em->clear();

        $progress->finish();
    }

    /**
     * @param string $shortcut
     *
     * @return array
     */
    private function parseShortcutNotation(string $shortcut): array
    {
        $entity = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($entity, ':')) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The entity name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Blog/Post)',
                    $entity
                )
            );
        }

        return [substr($entity, 0, $pos), substr($entity, $pos + 1)];
    }
}
