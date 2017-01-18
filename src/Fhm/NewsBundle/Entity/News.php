<?php
namespace Fhm\NewsBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class News extends Fhm
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $subtitle;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $resume;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $content;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_start;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_end;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\MediaBundle\Entity\Media", cascade={"all"})
     */
    protected $image;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\GalleryBundle\Entity\Gallery", cascade={"all"})
     */
    protected $gallery;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\UserBundle\Entity\User", cascade={"all"})
     */
    protected $author;

    /**
     * @ORM\OneToMany(targetEntity="Fhm\NewsBundle\Entity\NewsGroup", cascade={"all"})
     */
    protected $newsgroups;

    /**
     * @ORM\Column(type="integer")
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
     * @param \Fhm\MediaBundle\Entity\Media $media
     *
     * @return self
     */
    public function setImage($media)
    {
        $this->image = ($media instanceof \Fhm\MediaBundle\Entity\Media) ? $media : null;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Fhm\MediaBundle\Entity\Media $media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set gallery
     *
     * @param \Fhm\GalleryBundle\Entity\Gallery $gallery
     *
     * @return self
     */
    public function setGallery($gallery)
    {
        $this->gallery = ($gallery instanceof \Fhm\GalleryBundle\Entity\Gallery) ? $gallery : null;

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
     * @param \Fhm\UserBundle\Entity\User $user
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
     * @return \Fhm\UserBundle\Entity\User $user
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
     * @param \Fhm\NewsBundle\Entity\NewsGroup $newsgroup
     *
     * @return $this
     */
    public function addNewsgroup(\Fhm\NewsBundle\Entity\NewsGroup $newsgroup)
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
     * @param \Fhm\NewsBundle\Entity\NewsGroup $newsgroup
     *
     * @return $this
     */
    public function removeNewsgroup(\Fhm\NewsBundle\Entity\NewsGroup $newsgroup)
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
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetNewsgroups();

        return parent::preRemove();
    }
}