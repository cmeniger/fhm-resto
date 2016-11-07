<?php
namespace Fhm\UserBundle\OAuth;

use Fhm\UserBundle\Document\User;
use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Loading and ad-hoc creation of a user by an OAuth sign-in provider account.
 *
 * @author Fabian Kiss <fabian.kiss@ymc.ch>
 */
class UserProvider extends FOSUBUserProvider
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try
        {
            return parent::loadUserByOAuthUserResponse($response);
        }
        catch(UsernameNotFoundException $e)
        {
            if(null === $user = $this->userManager->findUserByEmail($response->getEmail()))
            {
                return $this->createUserByOAuthUserResponse($response);
            }

            return $this->updateUserByOAuthUserResponse($user, $response);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();
        $uniqueId     = $response->getEmail();
        $user->addOAuthAccount($providerName, $uniqueId);
        $this->userManager->updateUser($user);
    }

    /**
     * Ad-hoc creation of user
     *
     * @param UserResponseInterface $response
     *
     * @return User
     */
    protected function createUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->userManager->createUser();
        $this->updateUserByOAuthUserResponse($user, $response);
        $user->setEmail($response->getEmail());
        $user->setImageFacebook($response->getProfilePicture());
        
//        if(!$user->getPassword()) {
//            // generate unique token
//            $secret = md5(uniqid(rand(), true));
//            $user->setPassword($secret);
//        }
        $user->setUsername($response->getNickname() ? $response->getNickname() : $response->getEmail());
        $user->setEnabled(true);

        return $user;
    }

    /**
     * Attach OAuth sign-in provider account to existing user
     *
     * @param FOSUserInterface      $user
     * @param UserResponseInterface $response
     *
     * @return FOSUserInterface
     */
    protected function updateUserByOAuthUserResponse(FOSUserInterface $user, UserResponseInterface $response)
    {
        $providerName       = $response->getResourceOwner()->getName();
        $providerNameSetter = 'setId' . ucfirst($providerName);
        $user->$providerNameSetter($response->getEmail());
        $user->setImageFacebook($response->getProfilePicture());
//        if(!$user->getPassword()) {
//            // generate unique token
//            $secret = md5(uniqid(rand(), true));
//            $user->setPassword($secret);
//        }
        $user->setUsername($response->getNickname() ? $response->getNickname() : $response->getEmail());

        return $user;
    }

    /**
     * @param $pass
     *
     * @return string
     */
    function encryptPass( $pass ) {
        $cryptKey      = 'qJB0rGtIn5UB1xG03efyCp';
        $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $pass, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
        return( $qEncoded );
    }

    /**
     * @param $pass
     *
     * @return string
     */
    function decryptPass( $pass ) {
        $cryptKey      = 'qJB0rGtIn5UB1xG03efyCp';
        $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $pass ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
        return( $qDecoded );
    }
}