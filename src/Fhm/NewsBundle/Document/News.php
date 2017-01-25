<?php
namespace Fhm\NewsBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * News
 * @MongoDB\Document(repositoryClass="Fhm\NewsBundle\Document\Repository\NewsRepository")
 */
class News extends FhmFhm
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
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_start;

    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_end;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\MediaBundle\Document\Media", nullable=true, cascade={"all"})
     */
    protected $image;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\GalleryBundle\Document\Gallery", nullable=true, cascade={"all"})
     */
    protected $gallery;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Fhm\UserBundle\Document\User", nullable=true, cascade={"all"})
     */
    protected $author;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Fhm\NewsBundle\Document\NewsGroup", nullable=true, cascade={"all"})
     */
    protected $newsgroups;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort_newsgroup;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->newsgroups     = new ArrayCollection();
        $this->date_start     = null;
        $this->date_end       = null;
        $this->sort_newsgroup = 0;
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
     * Set date start
     *
     * @param \datetime $dateStart
     *
     * @return self
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get date start
     *
     * @return \datetime $dateStart
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set date end
     *
     * @param \datetime $dateEnd
     *
     * @return self
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;

        return $this;
    }

    /**
     * Get date end
     *
     * @return \datetime $dateEnd
     */
    public function getDateEnd()
    {
        return $this->date_end;
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

    /**
     * Get newsgroups
     *
     * @return mixed
     */
    public function getNewsgroups()
    {
        return $this->newsgroups;
    }

    /**
     * Set newsgroups
     *
     * @param ArrayCollection $newsgroups
     *
     * @return $this
     */
    public function setNewsgroups($newsgroups)
    {
        $this->resetNewsgroups();
        foreach($newsgroups as $newsgroup)
        {
            $newsgroup->addNews($this);
        }
        $this->newsgroups = $newsgroups;

        return $this;
    }

    /**
     * Add newsgroup
     *
     * @param \Fhm\NewsBundle\Document\NewsGroup $newsgroup
     *
     * @return $this
     */
    public function addNewsgroup(\Fhm\NewsBundle\Document\NewsGroup $newsgroup)
    {
        if(!$this->newsgroups->contains($newsgroup))
        {
            $this->newsgroups->add($newsgroup);
            $newsgroup->addNews($this);
        }

        return $this;
    }

    /**
     * Remove newsgroup
     *
     * @param \Fhm\NewsBundle\Document\NewsGroup $newsgroup
     *
     * @return $this
     */
    public function removeNewsgroup(\Fhm\NewsBundle\Document\NewsGroup $newsgroup)
    {
        if($this->newsgroups->contains($newsgroup))
        {
            $this->newsgroups->removeElement($newsgroup);
            $newsgroup->removeNews($this);
        }

        return $this;
    }

    /**
     * Reset newsgroups
     *
     * @return $this
     */
    public function resetNewsgroups()
    {
        foreach($this->newsgroups as $newsgroup)
        {
            $newsgroup->removeNews($this);
        }
        $this->newsgroups = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_newsgroup = $this->newsgroups->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetNewsgroups();

        return parent::preRemove();
    }
}