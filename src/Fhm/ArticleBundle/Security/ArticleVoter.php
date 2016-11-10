<?php
namespace Fhm\ArticleBundle\Security;

use Core\UserBundle\Document\User;
use Fhm\ArticleBundle\Document\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

/**
 * Created by PhpStorm.
 * User: rcisse
 * Date: 10/11/16
 * Time: 12:41
 */
class ArticleVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Article) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var Article $post */
        $document = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($document, $user);
            case self::EDIT:
                return $this->canEdit($document, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Article $document, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($document, $user)) {
            return true;
        }

        // the Document object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return !$document->isPrivate();
    }

    private function canEdit(Article $document, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $document->getOwner();
    }

}