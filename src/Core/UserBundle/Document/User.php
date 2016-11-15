<?php

namespace Core\UserBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $dateActivity;

    /**
     * @return mixed
     */
    public function getDateActivity()
    {
        return $this->dateActivity;
    }

    /**
     * @param mixed $dateActivity
     * @return User
     */
    public function setDateActivity($dateActivity)
    {
        $this->dateActivity = $dateActivity;

        return $this;
    }




}