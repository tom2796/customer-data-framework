<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace CustomerManagementFrameworkBundle\Security\UserProvider;

use CustomerManagementFrameworkBundle\CustomerProvider\CustomerProviderInterface;
use CustomerManagementFrameworkBundle\Model\CustomerInterface;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CustomerObjectUserProvider implements UserProviderInterface
{
    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    /**
     * @var string
     */
    protected $usernameField = 'email';

    public function __construct(
        CustomerProviderInterface $customerProvider,
        string $usernameField = 'email'
    ) {
        $this->customerProvider = $customerProvider;
        $this->usernameField = $usernameField;
    }

    /**
     * @inheritdoc
     */
    public function loadUserByIdentifier(string $identifier)
    {
        $list = $this->customerProvider->getList();
        $list->setCondition(sprintf('%s = ?', $this->usernameField), $identifier);
        $this->customerProvider->addActiveCondition($list);

        /** @var CustomerInterface|false $customer */
        $customer = $list->current();

        if (!$customer) {
            throw new UserNotFoundException(sprintf('Customer "%s" was not found', $identifier));
        }

        return $customer;
    }

    /**
     * @deprecated use loadUserByIdentifier() instead.
     *
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        $class = $this->customerProvider->getCustomerClassName();
        if (!$user instanceof $class || !$user instanceof AbstractObject) {
            throw new UnsupportedUserException();
        }

        return $this->customerProvider->getById($user->getId(), true);
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        if ($class === $this->customerProvider->getCustomerClassName()) {
            return true;
        }

        return false;
    }
}
