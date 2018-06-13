<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Templating\EngineInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\NotificationBundle\Component\UniOne;

/**
 * Class CustomerConsumer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Customer\Consumer
 */
class ResetPasswordConsumer implements ResetPasswordConsumerInterface
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
        /** @var Customer $customer */
        $customer = unserialize($message->getBody());

        if (false === empty($customer->getEmail())) {
            $message = $this->createResetPasswordMessage($customer);
            print_r("Sending email message via UniOne\n");
            $response = $this->uniOne->sendEmail($message);
            print_r(sprintf("UniOne Response: %s\n", $response->getBody()->getContents()));
        }

        print_r(sprintf(
            "Customer: %s\nPhone: %s\nReset Password Code: %s\nEmail: %s\n",
            $customer->getId()->toString(),
            $customer->getPhone(),
            $customer->getResetPasswordCode(),
            $customer->getEmail()
        ));

        return true;
    }

    /**
     * @param Customer $customer
     *
     * @return array
     */
    private function createResetPasswordMessage(Customer $customer): array
    {
        return [
            'body' => [
                'html' => $this->templating->render(
                    'UmberFirmNotificationBundle:Default/html:reset_password.html.twig',
                    [
                        'resetPasswordLink' => sprintf(
                            "%s/%s",
                            'http://ufsoa-posh-ua-master.fppfrp22sh.eu-west-1.elasticbeanstalk.com/reset-password',
                            $customer->getResetPasswordCode()
                        ),
                    ]
                ),
                'plaintext' => $this->templating->render(
                    'UmberFirmNotificationBundle:Default/plaintext:reset_password.html.twig',
                    [
                        'resetPasswordLink' => sprintf(
                            "%s/%s",
                            'http://ufsoa-posh-ua-master.fppfrp22sh.eu-west-1.elasticbeanstalk.com/reset-password',
                            $customer->getResetPasswordCode()
                        ),
                    ]
                ),
            ],
            'subject' => 'POSH.UA 2.0 | Reset Password',
            'recipients' => [
                [
                    'email' => $customer->getEmail(),
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
