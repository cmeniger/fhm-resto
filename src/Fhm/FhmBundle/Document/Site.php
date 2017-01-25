<?php
namespace Fhm\FhmBundle\Document;

use Fhm\ContactBundle\Document\Contact;
use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Site
 * @MongoDB\Document(repositoryClass="Fhm\FhmBundle\Document\Repository\SiteRepository")
 * @MongoDBUnique(fields="name")
 */
class Site extends FhmFhm
{
    /**
     * @MongoDB\Field(type="string")
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\FhmBundle\Document\Menu", nullable=true)
     */
    protected $menu;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\SliderBundle\Document\Slider", nullable=true)
     */
    protected $slider;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\GalleryBundle\Document\Gallery", nullable=true)
     */
    protected $gallery;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", cascade={"persist"}, nullable=true)
     */
    protected $logo;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", cascade={"persist"}, nullable=true)
     */
    protected $background;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\NewsBundle\Document\NewsGroup", nullable=true)
     */
    protected $news;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\PartnerBundle\Document\PartnerGroup", nullable=true)
     */
    protected $partner;
    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\ContactBundle\Document\Contact", nullable=true)
     */
    protected $contact;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_facebook;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_facebook_id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_twitter;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_twitter_id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_google;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_google_id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_youtube;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_instagram;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $social_flux;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $default;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $legal_notice;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->default = false;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return self
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set menu
     *
     * @param \Fhm\FhmBundle\Document\Menu $menu
     *
     * @return $this
     */
    public function setMenu($menu)
    {
        $this->menu = ($menu instanceof \Fhm\FhmBundle\Document\Menu) ? $menu : null;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Fhm\FhmBundle\Document\Menu $menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set slider
     *
     * @param \Fhm\SliderBundle\Document\Slider $slider
     *
     * @return self
     */
    public function setSlider($slider)
    {
        $this->slider = ($slider instanceof \Fhm\SliderBundle\Document\Slider) ? $slider : null;

        return $this;
    }

    /**
     * Get slider
     *
     * @return \Fhm\SliderBundle\Document\Slider $slider
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * Set gallery
     *
     * @param \Fhm\GalleryBundle\Document\Gallery $gallery
     *
     * @return self
     */
    public function setGallery($gallery)
    {
        $this->gallery = ($gallery instanceof \Fhm\GalleryBundle\Document\Gallery) ? $gallery : null;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return mixed
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set logo
     *
     * @param \Fhm\MediaBundle\Document\Media $media
     *
     * @return self
     */
    public function setLogo($media)
    {
        $this->logo = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \Fhm\MediaBundle\Document\Media $logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set background
     *
     * @param \Fhm\MediaBundle\Document\Media $media
     *
     * @return self
     */
    public function setBackground($media)
    {
        $this->background = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * Get background
     *
     * @return \Fhm\MediaBundle\Document\Media $background
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Set news
     *
     * @param \Fhm\NewsBundle\Document\NewsGroup $news
     *
     * @return self
     */
    public function setNews($news)
    {
        $this->news = ($news instanceof \Fhm\NewsBundle\Document\NewsGroup) ? $news : null;

        return $this;
    }

    /**
     * Get news
     *
     * @return \Fhm\NewsBundle\Document\NewsGroup $news
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set partner
     *
     * @param \Fhm\PartnerBundle\Document\PartnerGroup $partner
     *
     * @return self
     */
    public function setPartner($partner)
    {
        $this->partner = ($partner instanceof \Fhm\PartnerBundle\Document\PartnerGroup) ? $partner : null;

        return $this;
    }

    /**
     * Get partner
     *
     * @return \Fhm\PartnerBundle\Document\PartnerGroup $partner
     */
    public function getPartner()
    {
        return $this->partner;
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
     * Set default
     *
     * @param boolean $default
     *
     * @return self
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return boolean $default
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param Contact $contact
     *
     * @return $this
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLegalNotice()
    {
        return $this->legal_notice;
    }

    /**
     * @param mixed $legal_notice
     */
    public function setLegalNotice($legal_notice)
    {
        $this->legal_notice = $legal_notice;
    }

}
