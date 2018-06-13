<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Order\Consumer;

use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\NotificationBundle\Component\UniOne;
use Symfony\Component\Templating\EngineInterface;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;

/**
 * Class OrderConsumer
 *
 * @package UmberFirm\Bundle\PublicBundle\Component\Order\Consumer
 */
class OrderConsumer implements OrderConsumerInterface
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
        /** @var PublicOrder $publicOrder */
        $publicOrder = unserialize($message->getBody());

        if (false === empty($publicOrder->getEmail())) {
            $message = $this->createOrderMessage($publicOrder);
            print_r("Sending email message via UniOne\n"); // rm or service message ?
            $response = $this->uniOne->sendEmail($message);
            print_r(sprintf("UniOne Response: %s\n", $response->getBody()->getContents())); // rm or service message ?
        }

        return true;
    }

    /**
     * @param PublicOrder $publicOrder
     *
     * @return array
     */
    private function createOrderMessage(PublicOrder $publicOrder): array
    {
        return [
            'body' => [
                'html' => $this->templating->render(
                    'UmberFirmNotificationBundle:Default/html:order.html.twig',
                    [
                        'fullname' => $publicOrder->getFullname(),
                        'number' => $publicOrder->getNumber(),
                    ]
                ),
                'plaintext' => $this->templating->render(
                    'UmberFirmNotificationBundle:Default/plaintext:order.html.twig',
                    [
                        'fullname' => $publicOrder->getFullname(),
                        'number' => $publicOrder->getNumber(),
                    ]
                ),
            ],
            'subject' => 'POSH.UA 2.0 | Reset Password',
            'recipients' => [
                [
                    'email' => $publicOrder->getEmail(),
                ],
            ],
        ];
    }

    /**
     * @param UniOne $uniOne
     */
    public function setUniOne(UniOne $uniOne)
    {
        $this->uniOne = $uniOne;
    }

    /**
     * @param EngineInterface $templating
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }
}
