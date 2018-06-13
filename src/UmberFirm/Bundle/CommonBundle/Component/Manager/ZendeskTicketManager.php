<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Component\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\ShopBundle\Entity\SettingsAttribute;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;
use Zendesk\API\HttpClient;
use Zendesk\API\Utilities\Auth;

/**
 * Class ZendeskTicketManager
 *
 * @package UmberFirm\Bundle\CommonBundle\Component\Manager
 */
class ZendeskTicketManager implements ZendeskTicketManagerInterface
{
    /**
     * @var HttpClient
     */
    private $zendesk;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $settings;

    /**
     * ZendeskTicketManager constructor.
     *
     * @param HttpClient $zendesk
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        HttpClient $zendesk,
        EntityManagerInterface $entityManager
    ) {
        $this->zendesk = $zendesk;
        $this->entityManager = $entityManager;
    }

    /**
     * Auth zendesk by current shop
     *
     * {@inheritdoc}
     */
    public function auth(Shop $shop): void
    {
        $this->settings = $this->getSettings($shop);

        if (
            false === array_key_exists(self::SETTING_USERNAME, $this->settings) ||
            false === array_key_exists(self::SETTING_TOKEN, $this->settings) ||
            false === array_key_exists(self::SETTING_BRAND, $this->settings)
        ) {
            throw new \InvalidArgumentException('Invalid Zendesk ShopSettings');
        }

        $this->zendesk->setAuth(
            Auth::BASIC,
            [
                'username' => $this->settings[self::SETTING_USERNAME],
                'token' => $this->settings[self::SETTING_TOKEN],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(string $action, Feedback $feedback): bool
    {
        $feedback = $this->entityManager->find(Feedback::class, $feedback->getId()->toString());
        $this->auth($feedback->getShop());

        try {
            return $this->$action($feedback);
        } catch (\Exception $exception) {
            //todo: log errors
            print_r($exception->getMessage());

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(Feedback $feedback): bool
    {
        $ticket = $this->zendesk->tickets()->create(
            [
                'subject' => $feedback->getSubject()->getName($feedback->getLocale()),
                'comment' => [
                    'body' => sprintf(
                        "%s \n %s: %s",
                        $feedback->getMessage(),
                        'Source', //TODO: add translations to this word
                        $feedback->getSource()
                    ),
                ],
                'requester' => array(
                    'name' => $feedback->getName(),
                    'email' => $feedback->getEmail(),
                ),
                'brand_id' => $this->settings[self::SETTING_BRAND],
            ]
        );

        if (null === $ticket) {
            return false;
        }

        $this->saveZendeskReference($feedback, $ticket);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Feedback $feedback): bool
    {
        return null === $this->zendesk->tickets()->delete((int) $feedback->getReference()) ? true : false;
    }

    /**
     * TODO: create ShopSettingsManager and store settings in cache
     *
     * @param Shop $shop
     *
     * @return array
     */
    protected function getSettings(Shop $shop): array
    {
        $settingRepository = $this->entityManager->getRepository(ShopSettings::class);

        return $settingRepository->findSetting(
            $shop,
            [
                self::SETTING_BRAND,
                self::SETTING_USERNAME,
                self::SETTING_TOKEN,
            ]
        );
    }

    /**
     * @param Feedback $feedback
     * @param \stdClass $ticket
     *
     * @return void
     */
    protected function saveZendeskReference(Feedback $feedback, \stdClass $ticket): void
    {
        $feedback = $this->entityManager->find(Feedback::class, $feedback->getId()->toString());
        $feedback->setReference((string) $ticket->ticket->id);
        $this->entityManager->persist($feedback);
        $this->entityManager->flush();
    }
}
