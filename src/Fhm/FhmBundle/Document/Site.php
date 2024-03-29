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
    protected $title_card_slider;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title_card_main;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title_card_forward;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title_testimony;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title_news;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title_partner;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle_card_slider;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle_card_main;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle_card_forward;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle_testimony;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle_news;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $subtitle_partner;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\FhmBundle\Document\Menu", nullable=true)
     */
    protected $menu;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\FhmBundle\Document\Menu", nullable=true)
     */
    protected $menu_home_left;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\FhmBundle\Document\Menu", nullable=true)
     */
    protected $menu_home_right;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\FhmBundle\Document\Menu", nullable=true)
     */
    protected $menu_home_side;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\FhmBundle\Document\Menu", nullable=true)
     */
    protected $menu_footer;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", cascade={"persist"}, nullable=true)
     */
    protected $logo;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", cascade={"persist"}, nullable=true)
     */
    protected $background_top;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", cascade={"persist"}, nullable=true)
     */
    protected $background_card;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", cascade={"persist"}, nullable=true)
     */
    protected $background_testimony;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\SliderBundle\Document\Slider", nullable=true)
     */
    protected $slider;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\GalleryBundle\Document\Gallery", nullable=true)
     */
    protected $gallery_top;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\GalleryBundle\Document\Gallery", nullable=true)
     */
    protected $gallery_bottom;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\CardBundle\Document\Card", nullable=true)
     */
    protected $card_slider;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\CardBundle\Document\Card", nullable=true)
     */
    protected $card_main;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\CardBundle\Document\Card", nullable=true)
     */
    protected $card_forward;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\ContactBundle\Document\Contact", nullable=true)
     */
    protected $contact;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\NewsBundle\Document\NewsGroup", nullable=true)
     */
    protected $news;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\PartnerBundle\Document\PartnerGroup", nullable=true)
     */
    protected $partner;

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
    protected $show_slider;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_gallery_top;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_gallery_bottom;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_card_slider;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_card_main;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_card_forward;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_testimony;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_contact;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_news;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $show_partner;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $demo;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $default;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->demo                = false;
        $this->default             = false;
        $this->show_slider         = false;
        $this->show_gallery_top    = false;
        $this->show_gallery_bottom = false;
        $this->show_card_slider    = false;
        $this->show_card_main      = false;
        $this->show_card_forward   = false;
        $this->show_testimony      = false;
        $this->show_contact        = false;
        $this->show_news           = false;
        $this->show_partner        = false;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleCardSlider()
    {
        return $this->title_card_slider;
    }

    /**
     * @param $title_card_slider
     *
     * @return $this
     */
    public function setTitleCardSlider($title_card_slider)
    {
        $this->title_card_slider = $title_card_slider;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleCardMain()
    {
        return $this->title_card_main;
    }

    /**
     * @param $title_card_main
     *
     * @return $this
     */
    public function setTitleCardMain($title_card_main)
    {
        $this->title_card_main = $title_card_main;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleCardForward()
    {
        return $this->title_card_forward;
    }

    /**
     * @param $title_card_forward
     *
     * @return $this
     */
    public function setTitleCardForward($title_card_forward)
    {
        $this->title_card_forward = $title_card_forward;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleTestimony()
    {
        return $this->title_testimony;
    }

    /**
     * @param $title_testimony
     *
     * @return $this
     */
    public function setTitleTestimony($title_testimony)
    {
        $this->title_testimony = $title_testimony;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleNews()
    {
        return $this->title_news;
    }

    /**
     * @param $title_news
     *
     * @return $this
     */
    public function setTitleNews($title_news)
    {
        $this->title_news = $title_news;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitlePartner()
    {
        return $this->title_partner;
    }

    /**
     * @param $title_partner
     *
     * @return $this
     */
    public function setTitlePartner($title_partner)
    {
        $this->title_partner = $title_partner;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param $subtitle
     *
     * @return $this
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtitleCardSlider()
    {
        return $this->subtitle_card_slider;
    }

    /**
     * @param $subtitle_card_slider
     *
     * @return $this
     */
    public function setSubtitleCardSlider($subtitle_card_slider)
    {
        $this->subtitle_card_slider = $subtitle_card_slider;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtitleCardMain()
    {
        return $this->subtitle_card_main;
    }

    /**
     * @param $subtitle_card_main
     *
     * @return $this
     */
    public function setSubtitleCardMain($subtitle_card_main)
    {
        $this->subtitle_card_main = $subtitle_card_main;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtitleCardForward()
    {
        return $this->subtitle_card_forward;
    }

    /**
     * @param $subtitle_card_forward
     *
     * @return $this
     */
    public function setSubtitleCardForward($subtitle_card_forward)
    {
        $this->subtitle_card_forward = $subtitle_card_forward;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtitleTestimony()
    {
        return $this->subtitle_testimony;
    }

    /**
     * @param $subtitle_testimony
     *
     * @return $this
     */
    public function setSubtitleTestimony($subtitle_testimony)
    {
        $this->subtitle_testimony = $subtitle_testimony;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtitleNews()
    {
        return $this->subtitle_news;
    }

    /**
     * @param $subtitle_news
     *
     * @return $this
     */
    public function setSubtitleNews($subtitle_news)
    {
        $this->subtitle_news = $subtitle_news;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtitlePartner()
    {
        return $this->subtitle_partner;
    }

    /**
     * @param $subtitle_partner
     *
     * @return $this
     */
    public function setSubtitlePartner($subtitle_partner)
    {
        $this->subtitle_partner = $subtitle_partner;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param $menu
     *
     * @return $this
     */
    public function setMenu($menu)
    {
        $this->menu = ($menu instanceof \Fhm\FhmBundle\Document\Menu) ? $menu : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuHomeLeft()
    {
        return $this->menu_home_left;
    }

    /**
     * @param $menu
     *
     * @return $this
     */
    public function setMenuHomeLeft($menu)
    {
        $this->menu_home_left = ($menu instanceof \Fhm\FhmBundle\Document\Menu) ? $menu : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuHomeRight()
    {
        return $this->menu_home_right;
    }

    /**
     * @param $menu
     *
     * @return $this
     */
    public function setMenuHomeRight($menu)
    {
        $this->menu_home_right = ($menu instanceof \Fhm\FhmBundle\Document\Menu) ? $menu : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuHomeSide()
    {
        return $this->menu_home_side;
    }

    /**
     * @param $menu
     *
     * @return $this
     */
    public function setMenuHomeSide($menu)
    {
        $this->menu_home_side = ($menu instanceof \Fhm\FhmBundle\Document\Menu) ? $menu : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuFooter()
    {
        return $this->menu_footer;
    }

    /**
     * @param $menu
     *
     * @return $this
     */
    public function setMenuFooter($menu)
    {
        $this->menu_footer = ($menu instanceof \Fhm\FhmBundle\Document\Menu) ? $menu : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param $media
     *
     * @return $this
     */
    public function setLogo($media)
    {
        $this->logo = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackgroundTop()
    {
        return $this->background_top;
    }

    /**
     * @param $media
     *
     * @return $this
     */
    public function setBackgroundTop($media)
    {
        $this->background_top = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackgroundCard()
    {
        return $this->background_card;
    }

    /**
     * @param $media
     *
     * @return $this
     */
    public function setBackgroundCard($media)
    {
        $this->background_card = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackgroundTestimony()
    {
        return $this->background_testimony;
    }

    /**
     * @param $media
     *
     * @return $this
     */
    public function setBackgroundTestimony($media)
    {
        $this->background_testimony = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @param $slider
     *
     * @return $this
     */
    public function setSlider($slider)
    {
        $this->slider = ($slider instanceof \Fhm\SliderBundle\Document\Slider) ? $slider : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGalleryTop()
    {
        return $this->gallery_top;
    }

    /**
     * @param $gallery
     *
     * @return $this
     */
    public function setGalleryTop($gallery)
    {
        $this->gallery_top = ($gallery instanceof \Fhm\GalleryBundle\Document\Gallery) ? $gallery : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGalleryBottom()
    {
        return $this->gallery_bottom;
    }

    /**
     * @param $gallery
     *
     * @return $this
     */
    public function setGalleryBottom($gallery)
    {
        $this->gallery_bottom = ($gallery instanceof \Fhm\GalleryBundle\Document\Gallery) ? $gallery : null;

        return $this;
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
        $this->contact = ($contact instanceof \Fhm\ContactBundle\Document\Contact) ? $contact : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardSlider()
    {
        return $this->card_slider;
    }

    /**
     * @param $card
     *
     * @return $this
     */
    public function setCardSlider($card)
    {
        $this->card_slider = ($card instanceof \Fhm\CardBundle\Document\Card) ? $card : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardMain()
    {
        return $this->card_main;
    }

    /**
     * @param $card
     *
     * @return $this
     */
    public function setCardMain($card)
    {
        $this->card_main = ($card instanceof \Fhm\CardBundle\Document\Card) ? $card : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardForward()
    {
        return $this->card_forward;
    }

    /**
     * @param $card
     *
     * @return $this
     */
    public function setCardForward($card)
    {
        $this->card_forward = ($card instanceof \Fhm\CardBundle\Document\Card) ? $card : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param $news
     *
     * @return $this
     */
    public function setNews($news)
    {
        $this->news = ($news instanceof \Fhm\NewsBundle\Document\NewsGroup) ? $news : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param $partner
     *
     * @return $this
     */
    public function setPartner($partner)
    {
        $this->partner = ($partner instanceof \Fhm\PartnerBundle\Document\PartnerGroup) ? $partner : null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialFacebook()
    {
        return $this->social_facebook;
    }

    /**
     * @param $socialFacebook
     *
     * @return $this
     */
    public function setSocialFacebook($socialFacebook)
    {
        $this->social_facebook = $socialFacebook;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialFacebookId()
    {
        return $this->social_facebook_id;
    }

    /**
     * @param $socialFacebookId
     *
     * @return $this
     */
    public function setSocialFacebookId($socialFacebookId)
    {
        $this->social_facebook_id = $socialFacebookId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialTwitter()
    {
        return $this->social_twitter;
    }

    /**
     * @param $socialTwitter
     *
     * @return $this
     */
    public function setSocialTwitter($socialTwitter)
    {
        $this->social_twitter = $socialTwitter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialTwitterId()
    {
        return $this->social_twitter_id;
    }

    /**
     * @param $socialTwitterId
     *
     * @return $this
     */
    public function setSocialTwitterId($socialTwitterId)
    {
        $this->social_twitter_id = $socialTwitterId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialGoogle()
    {
        return $this->social_google;
    }

    /**
     * @param $socialGoogle
     *
     * @return $this
     */
    public function setSocialGoogle($socialGoogle)
    {
        $this->social_google = $socialGoogle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialGoogleId()
    {
        return $this->social_google_id;
    }

    /**
     * @param $socialGoogleId
     *
     * @return $this
     */
    public function setSocialGoogleId($socialGoogleId)
    {
        $this->social_google_id = $socialGoogleId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialYoutube()
    {
        return $this->social_youtube;
    }

    /**
     * @param $socialYoutube
     *
     * @return $this
     */
    public function setSocialYoutube($socialYoutube)
    {
        $this->social_youtube = $socialYoutube;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialInstagram()
    {
        return $this->social_instagram;
    }

    /**
     * @param $socialInstagram
     *
     * @return $this
     */
    public function setSocialInstagram($socialInstagram)
    {
        $this->social_instagram = $socialInstagram;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSocialFlux()
    {
        return $this->social_flux;
    }

    /**
     * @param $socialFlux
     *
     * @return $this
     */
    public function setSocialFlux($socialFlux)
    {
        $this->social_flux = $socialFlux;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDemo()
    {
        return $this->demo;
    }

    /**
     * @param $demo
     *
     * @return $this
     */
    public function setDemo($demo)
    {
        $this->demo = $demo;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowSlider()
    {
        return $this->show_slider;
    }

    /**
     * @param $show_slider
     *
     * @return $this
     */
    public function setShowSlider($show_slider)
    {
        $this->show_slider = $show_slider;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowGalleryTop()
    {
        return $this->show_gallery_top;
    }

    /**
     * @param $show_gallery_top
     *
     * @return $this
     */
    public function setShowGalleryTop($show_gallery_top)
    {
        $this->show_gallery_top = $show_gallery_top;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowGalleryBottom()
    {
        return $this->show_gallery_bottom;
    }

    /**
     * @param $show_gallery_bottom
     *
     * @return $this
     */
    public function setShowGalleryBottom($show_gallery_bottom)
    {
        $this->show_gallery_bottom = $show_gallery_bottom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowCardSlider()
    {
        return $this->show_card_slider;
    }

    /**
     * @param $show_card_slider
     *
     * @return $this
     */
    public function setShowCardSlider($show_card_slider)
    {
        $this->show_card_slider = $show_card_slider;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowCardMain()
    {
        return $this->show_card_main;
    }

    /**
     * @param $show_card_main
     *
     * @return $this
     */
    public function setShowCardMain($show_card_main)
    {
        $this->show_card_main = $show_card_main;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowCardForward()
    {
        return $this->show_card_forward;
    }

    /**
     * @param $show_card_forward
     *
     * @return $this
     */
    public function setShowCardForward($show_card_forward)
    {
        $this->show_card_forward = $show_card_forward;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowTestimony()
    {
        return $this->show_testimony;
    }

    /**
     * @param $show_testimony
     *
     * @return $this
     */
    public function setShowTestimony($show_testimony)
    {
        $this->show_testimony = $show_testimony;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowContact()
    {
        return $this->show_contact;
    }

    /**
     * @param $show_contact
     *
     * @return $this
     */
    public function setShowContact($show_contact)
    {
        $this->show_contact = $show_contact;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowNews()
    {
        return $this->show_news;
    }

    /**
     * @param $show_news
     *
     * @return $this
     */
    public function setShowNews($show_news)
    {
        $this->show_news = $show_news;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowPartner()
    {
        return $this->show_partner;
    }

    /**
     * @param $show_partner
     *
     * @return $this
     */
    public function setShowPartner($show_partner)
    {
        $this->show_partner = $show_partner;

        return $this;
    }

    /**
     * @param $bloc
     * @param $value
     *
     * @return $this
     */
    public function setShow($bloc, $value)
    {
        $bloc        = 'show_' . $bloc;
        $this->$bloc = $value;

        return $this;
    }

    /**
     * @param $bloc
     *
     * @return bool
     */
    public function isShow($bloc)
    {
        if($this->demo)
        {
            return true;
        }
        $vars = get_object_vars($this);
        foreach($vars as $key => $value)
        {
            if($key === 'show_' . $bloc)
            {
                return $value;
            }
        }

        return false;
    }
}
