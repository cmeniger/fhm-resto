<?php
namespace Fhm\ArticleBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\SerializerBundle\Annotation\Type;
use JMS\Serializer\Annotation as Serializer;

/**
 * Article
 * @MongoDB\Document(repositoryClass="Fhm\ArticleBundle\Repository\ArticleRepository")
 * @Serializer\AccessType("protected_method")
 */
class Article extends FhmFhm
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
     * @MongoDB\Field(type="string")
     */
    protected $resume;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true)
     */
    protected $image;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\GalleryBundle\Document\Gallery", nullable=true)
     */
    protected $gallery;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\UserBundle\Document\User", nullable=true)
     */
    protected $author;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
        $this->name  = $title;

        return $this;
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

        return $this;
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
     * Set resume
     *
     * @param string $resume
     *
     * @return self
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string $resume
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set image
     *
     * @param \Fhm\MediaBundle\Document\Media $media
     *
     * @return self
     */
    public function setImage($media)
    {
        $this->image = ($media instanceof \Fhm\MediaBundle\Document\Media) ? $media : null;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Fhm\MediaBundle\Document\Media $media
     */
    public function getImage()
    {
        return $this->image;
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
     * Set author
     *
     * @param \Fhm\UserBundle\Document\User $user
     *
     * @return self
     */
    public function setAuthor($user)
    {
        $this->author = $user;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Fhm\UserBundle\Document\User $user
     */
    public function getAuthor()
    {
        return $this->author;
    }
}