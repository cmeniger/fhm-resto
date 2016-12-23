<?php
namespace Fhm\UserBundle\Security;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserProvider
 * @package Fhm\UserBundle\Security
 */
class UserProvider implements UserProviderInterface
{
    private $userManager;

    /**
     * UserProvider constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param string $username
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('No user with name "%s" was found.', $username));
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return mixed
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->userManager->refreshUser($user);
    }

    /**
     * @param string $class
     * @return mixed
     */
    public function supportsClass($class)
    {
        return $this->userManager->supportsClass($class);
    }
}