<?php

namespace UmberFirm\Bundle\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command helps encode JSON to JWT token
 * Example of using:
 * bin/console umberfirm:jwt:encode '{"shop":"22222222222222222222222222222222"}'
 *
 * Class UmberFirmEncodeCommand
 *
 * @package UmberFirm\Bundle\CommonBundle\Command
 */
class UmberFirmEncodeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('umberfirm:jwt:encode')
            ->setDescription('Encode json string into JWT token. {"shop": "00000000000000000000000000000000"}')
            ->addArgument('json', InputArgument::REQUIRED, 'The username of the user.');
    }

    /**
     * {@inheritdoc}
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encoder = $this->getContainer()->get('lexik_jwt_authentication.encoder');
        $token = $encoder->encode(json_decode($input->getArgument('json'), true));

        echo $token;
    }
}
