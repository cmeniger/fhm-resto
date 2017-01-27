<?php
namespace Fhm\UserBundle\Entity;
use Fhm\GeolocationBundle\Entity\GeolocationWithUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Fhm\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="email")
 * @UniqueEntity(fields="emailCanonical")
 * @UniqueEntity(fields="username")
 * @UniqueEntity(fields="usernameCanonical")
 */
class User extends GeolocationWithUser
{
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $id_facebook;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $image_facebook;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $id_twitter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $id_google;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Type("\DateTime")
     */
    protected $date_activity;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $tel1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $tel2;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Type("\DateTime")
     */
    protected $birth_date;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email()
     */
    protected $emailCanonical;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $usernameCanonical;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $sign;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $sex;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", cascade={"persist"}, orphanRemoval=true)
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_facebook;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_facebook_id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_twitter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_twitter_id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_google;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_google_id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_youtube;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_instagram;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_flux;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $social_site;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->share  = false;
        $this->global = false;
    }

    /**
     * Set dateActivity
     *
     * @param date $dateActivity
     *
     * @return self
     */
    public function setDateActivity($dateActivity)
    {
        $this->date_activity = $dateActivity;

        return $this;
    }

    /**
     * Get dateActivity
     *
     * @return date $dateActivity
     */
    public function getDateActivity()
    {
        return $this->date_activity;
    }

    /**
     * Set first_name
     *
     * @param string $first_name
     *
     * @return self
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string $first_name
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $last_name
     *
     * @return self
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string $last_name
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set birth_date
     *
     * @param date $birth_date
     *
     * @return self
     */
    public function setBirthDate($birth_date)
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    /**
     * Get birth_date
     *
     * @return date $birth_date
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * Set tel1
     *
     * @param string $tel1
     *
     * @return self
     */
    public function setTel1($tel1)
    {
        $this->tel1 = $tel1;

        return $this;
    }

    /**
     * Get tel1
     *
     * @return string $tel1
     */
    public function getTel1()
    {
        return $this->tel1;
    }

    /**
     * Set tel2
     *
     * @param string $tel2
     *
     * @return self
     */
    public function setTel2($tel2)
    {
        $this->tel2 = $tel2;

        return $this;
    }

    /**
     * Get tel2
     *
     * @return string $tel2
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * Set sign
     *
     * @param string $sign
     *
     * @return self
     */
    public function setSign($sign)
    {
        $this->sign = $sign;

        return $this;
    }

    /**
     * Get sign
     *
     * @return string $sign
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return self
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string $sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set avatar
     *
     * @param \Fhm\MediaBundle\Entity\Media $media
     *
     * @return self
     */
    public function setAvatar($media)
    {
        $this->avatar = ($media instanceof \Fhm\MediaBundle\Entity\Media) ? $media : null;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Fhm\MediaBundle\Entity\Media $avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set socialFacebook
     *
     * @param string $socialFacebook
     *
     * @return self
     */
    public function setSocialFacebook($socialFacebook)
    {
        $this->social_facebook = $socialFacebook;

        return $this;
    }

    /**
     * Get socialFacebook
     *
     * @return string $socialFacebook
     */
    public function getSocialFacebook()
    {
        return $this->social_facebook;
    }

    /**
     * Set socialFacebookId
     *
     * @param string $socialFacebookId
     *
     * @return self
     */
    public function setSocialFacebookId($socialFacebookId)
    {
        $this->social_facebook_id = $socialFacebookId;

        return $this;
    }

    /**
     * Get socialFacebookId
     *
     * @return string $socialFacebookId
     */
    public function getSocialFacebookId()
    {
        return $this->social_facebook_id;
    }

    /**
     * Set socialTwitter
     *
     * @param string $socialTwitter
     *
     * @return self
     */
    public function setSocialTwitter($socialTwitter)
    {
        $this->social_twitter = $socialTwitter;

        return $this;
    }

    /**
     * Get socialTwitter
     *
     * @return string $socialTwitter
     */
    public function getSocialTwitter()
    {
        return $this->social_twitter;
    }

    /**
     * Set socialTwitterId
     *
     * @param string $socialTwitterId
     *
     * @return self
     */
    public function setSocialTwitterId($socialTwitterId)
    {
        $this->social_twitter_id = $socialTwitterId;

        return $this;
    }

    /**
     * Get socialTwitterId
     *
     * @return string $socialTwitterId
     */
    public function getSocialTwitterId()
    {
        return $this->social_twitter_id;
    }

    /**
     * Set socialGoogle
     *
     * @param string $socialGoogle
     *
     * @return self
     */
    public function setSocialGoogle($socialGoogle)
    {
        $this->social_google = $socialGoogle;

        return $this;
    }

    /**
     * Get socialGoogle
     *
     * @return string $socialGoogle
     */
    public function getSocialGoogle()
    {
        return $this->social_google;
    }

    /**
     * Set socialGoogleId
     *
     * @param string $socialGoogleId
     *
     * @return self
     */
    public function setSocialGoogleId($socialGoogleId)
    {
        $this->social_google_id = $socialGoogleId;

        return $this;
    }

    /**
     * Get socialGoogleId
     *
     * @return string $socialGoogleId
     */
    public function getSocialGoogleId()
    {
        return $this->social_google_id;
    }

    /**
     * Set socialYoutube
     *
     * @param string $socialYoutube
     *
     * @return self
     */
    public function setSocialYoutube($socialYoutube)
    {
        $this->social_youtube = $socialYoutube;

        return $this;
    }

    /**
     * Get socialYoutube
     *
     * @return string $socialYoutube
     */
    public function getSocialYoutube()
    {
        return $this->social_youtube;
    }

    /**
     * Set socialInstagram
     *
     * @param string $socialInstagram
     *
     * @return self
     */
    public function setSocialInstagram($socialInstagram)
    {
        $this->social_instagram = $socialInstagram;

        return $this;
    }

    /**
     * Get socialInstagram
     *
     * @return string $socialInstagram
     */
    public function getSocialInstagram()
    {
        return $this->social_instagram;
    }

    /**
     * Set socialFlux
     *
     * @param string $socialFlux
     *
     * @return self
     */
    public function setSocialFlux($socialFlux)
    {
        $this->social_flux = $socialFlux;

        return $this;
    }

    /**
     * Get socialFlux
     *
     * @return string $socialFlux
     */
    public function getSocialFlux()
    {
        return $this->social_flux;
    }

    /**
     * Set socialSite
     *
     * @param string $socialSite
     *
     * @return self
     */
    public function setSocialSite($socialSite)
    {
        $this->social_site = $socialSite;

        return $this;
    }

    /**
     * Get socialSite
     *
     * @return string $socialSite
     */
    public function getSocialSite()
    {
        return $this->social_site;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->setName($username);
        $this->setAlias($username);

        return $this;
    }

    /**
     * @param bool $boolean
     *
     * @return $this
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (Boolean) $boolean;
        $this->active  = (Boolean) $boolean;

        return $this;
    }

    /**
     * @param bool $boolean
     *
     * @return $this
     */
    public function setActive($boolean)
    {
        $this->setEnabled($boolean);

        return $this;
    }


    /**
     * @return mixed
     */
    public function getImageFacebook()
    {
        return $this->image_facebook;
    }

    /**
     * @param mixed $image_facebook
     */
    public function setImageFacebook($image_facebook)
    {
        $this->image_facebook = $image_facebook;
    }



    /**
     * Get CSV header
     *
     * @return array
     */
    public function getCsvHeader()
    {
        return array(
            'id',
            'username',
            'first_name',
            'last_name',
            'email',
            'date_create',
            'date_update',
            'delete',
            'active'
        );
    }

    /**
     * Get CSV data
     *
     * @return array
     */
    public function getCsvData()
    {
        return array(
            utf8_decode($this->id),
            utf8_decode($this->username),
            utf8_decode($this->first_name),
            utf8_decode($this->last_name),
            utf8_decode($this->emailCanonical),
            ($this->date_create) ? $this->date_create->format('d/m/Y H:i:s') : '',
            ($this->date_update) ? $this->date_update->format('d/m/Y H:i:s') : '',
            $this->delete,
            $this->enabled
        );
    }

    /**
     * Set CSV data
     *
     * @param array $data
     *
     * @return self
     */
    public function setCsvData($data)
    {
        $this->username          = (isset($data['username'])) ? $data['username'] : $this->username;
        $this->usernameCanonical = (isset($data['username'])) ?
            strtolower($data['username']) :
            $this->usernameCanonical;
        $this->email             = (isset($data['email'])) ? $data['email'] : $this->email;
        $this->emailCanonical    = (isset($data['email'])) ? strtolower($data['email']) : $this->emailCanonical;
        $this->first_name        = (isset($data['first_name'])) ? $data['first_name'] : $this->first_name;
        $this->last_name         = (isset($data['last_name'])) ? $data['last_name'] : $this->last_name;

        return $this;
    }

    /**
     * Get Roles list format
     *
     * @return array
     */
    public function getRolesList()
    {
        $list  = array();
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            $list[$role] = $role;
        }

        return $list;
    }
}