<?php
namespace Fhm\ContactBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fhm\GeolocationBundle\Entity\Geolocation;

/**
 * @ORM\Entity(repositoryClass="Fhm\ContactBundle\Entity\Repository\ContactRepository")
 * @ORM\Table()
 */
class Contact extends Geolocation
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $email = '';

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $phone = '';

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $fax = '';

    /**
     * @ORM\Column(type="boolean")
     */
    protected $form;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $form_template;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $profile;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", orphanRemoval=true, cascade={"persist"})
     */
    protected $profile_image;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $profile_template;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $social_facebook;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $social_twitter;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $social_google;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $social_youtube;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $social_instagram;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $social_flux;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $social_site;

    /**
     * @ORM\OneToMany(targetEntity="Fhm\ContactBundle\Entity\ContactMessage", mappedBy="contact")
     */
    protected $messages;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_message;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort_address;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->form = false;
        $this->messages = new ArrayCollection();
        $this->sort_message = 0;
        $this->sort_address = null;
    }

    /**
     * Get email
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return self
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string $fax
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set form
     *
     * @param boolean $form
     *
     * @return self
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return boolean $form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set form template
     *
     * @param string $form_template
     *
     * @return self
     */
    public function setFormTemplate($form_template)
    {
        $this->form_template = $form_template;

        return $this;
    }

    /**
     * Get form template
     *
     * @return string $form_template
     */
    public function getFormTemplate()
    {
        return $this->form_template;
    }

    /**
     * Set profile
     *
     * @param boolean $profile
     *
     * @return self
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return boolean $profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set profile image
     *
     * @param boolean $profile_image
     *
     * @return self
     */
    public function setProfileImage($profile_image)
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    /**
     * Get profile image
     *
     * @return boolean $profile_image
     */
    public function getProfileImage()
    {
        return $this->profile_image;
    }

    /**
     * Set profile template
     *
     * @param string $profile_template
     *
     * @return self
     */
    public function setProfileTemplate($profile_template)
    {
        $this->profile_template = $profile_template;

        return $this;
    }

    /**
     * Get profile template
     *
     * @return string $profile_template
     */
    public function getProfileTemplate()
    {
        return $this->profile_template;
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
     * Get messages
     *
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set messages
     *
     * @param ArrayCollection $messages
     *
     * @return $this
     */
    public function setMessages(ArrayCollection $messages)
    {
        $this->resetMessages();
        foreach ($messages as $message) {
            $message->setContact($this);
        }
        $this->messages = $messages;

        return $this;
    }

    /**
     * Add message
     *
     * @param \Fhm\ContactBundle\Entity\ContactMessage $message
     *
     * @return $this
     */
    public function addMessage(\Fhm\ContactBundle\Entity\ContactMessage $message)
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setContact($this);
        }

        return $this;
    }

    /**
     * Remove message
     *
     * @param \Fhm\ContactBundle\Entity\ContactMessage $message
     *
     * @return $this
     */
    public function removeMessage(\Fhm\ContactBundle\Entity\ContactMessage $message)
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            $message->removeContact();
        }

        return $this;
    }

    /**
     * Reset messages
     *
     * @return $this
     */
    public function resetMessages()
    {
        foreach ($this->messages as $message) {
            $message->removeContact();
        }
        $this->messages = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_message = $this->messages->count();
        $this->sort_address = strtolower($this->address_main);

        return parent::sortUpdate();
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetMessages();

        return parent::preRemove();
    }
}