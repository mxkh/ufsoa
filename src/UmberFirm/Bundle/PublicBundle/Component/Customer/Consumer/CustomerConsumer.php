<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Templating\EngineInterface;
use UmberFirm\Bundle\NotificationBundle\Component\UniOne;
use UmberFirm\Bundle\PublicBundle\Component\Customer\DataTransferObject\CustomerConfirmationCodeInterface;

/**
 * Class CustomerConsumer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Consumer
 */
class CustomerConsumer implements CustomerConsumerInterface
{
    /**
     * @var UniOne
     */
    private $uniOne;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $message)
    {
        /** @var CustomerConfirmationCodeInterface $customerConfirmationCode */
        $customerConfirmationCode = unserialize($message->getBody());

        if (false === empty($customerConfirmationCode->getEmail())) {
            $message = $this->createCustomerConfirmationEmailMessage($customerConfirmationCode);
            print_r("Sending email message via UniOne\n");
            $response = $this->uniOne->sendEmail($message);
            print_r(sprintf("UniOne Response: %s\n", $response->getBody()->getContents()));
        }

        print_r(sprintf(
            "Customer: %s\nPhone: %s\nCode: %s\nEmail: %s\n",
            $customerConfirmationCode->getId()->toString(),
            $customerConfirmationCode->getPhone(),
            $customerConfirmationCode->getCode(),
            $customerConfirmationCode->getEmail()
        ));

        return true;
    }

    /**
     * @param CustomerConfirmationCodeInterface $customerConfirmationCode
     *
     * @return array
     */
    private function createCustomerConfirmationEmailMessage(CustomerConfirmationCodeInterface $customerConfirmationCode): array
    {
        return [
            'body' => [
                'html' => $this->templating->render(
                    'UmberFirmNotificationBundle:Default/html:signup.html.twig',
                    [
                        'confirmationLink' => sprintf(
                            "%s/%s/%s",
                            'http://ufsoa-posh-ua-master.fppfrp22sh.eu-west-1.elasticbeanstalk.com/confirm',
                            $customerConfirmationCode->getId()->toString(),
                            $customerConfirmationCode->getCode()
                        ),
                    ]
                ),
                'plaintext' => $this->templating->render(
                    'UmberFirmNotificationBundle:Default/plaintext:signup.html.twig',
                    [
                        'confirmationLink' => sprintf(
                            "%s/%s/%s",
                            'http://ufsoa-posh-ua-master.fppfrp22sh.eu-west-1.elasticbeanstalk.com/confirm',
                            $customerConfirmationCode->getId()->toString(),
                            $customerConfirmationCode->getCode()
                        ),
                    ]
                ),
            ],
            'subject' => 'POSH.UA 2.0 | Signup confirmation',
            'recipients' => [
                [
                    'email' => $customerConfirmationCode->getEmail(),
                ],
            ]
        ];
    }

    public function setUniOne(UniOne $uniOne)
    {
        $this->uniOne = $uniOne;
    }

    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }
}
