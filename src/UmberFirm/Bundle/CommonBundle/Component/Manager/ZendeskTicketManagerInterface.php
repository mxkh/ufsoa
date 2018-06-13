<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Component\Manager;

use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface ZendeskTicketManagerInterface
 *
 * @package UmberFirm\Bundle\CommonBundle\Component\Manager
 */
interface ZendeskTicketManagerInterface
{
    const CREATE_ACTION = 'create';
    const REMOVE_ACTION = 'remove';

    const SETTING_USERNAME = 'zendesk_username';
    const SETTING_TOKEN = 'zendesk_token';
    const SETTING_BRAND = 'zendesk_brand';

    /**
     * @param Shop $shop
     *
     * @return void
     */
    public function auth(Shop $shop): void;

    /**
     * @param string $action
     * @param Feedback $feedback
     *
     * @return bool
     */
    public function execute(string $action, Feedback $feedback): bool;

    /**
     * Name of this method have to be the same to constant variable CREATE_ACTION
     *
     * @param Feedback $feedback
     *
     * @return bool
     */
    public function create(Feedback $feedback): bool;

    /**
     * Name of this method have to be the same to constant variable REMOVE_ACTION
     *
     * @param Feedback $feedback
     *
     * @return bool
     */
    public function remove(Feedback $feedback): bool;
}
