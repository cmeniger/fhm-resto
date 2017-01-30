<?php
namespace Fhm\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Fhm\NewsBundle\Entity\Repository\NewsGroupRepository")
 * @ORM\Table()
 */
class NewsGroup extends Fhm
{
    /**
     * @ORM\ManyToMany(targetEntity="Fhm\NewsBundle\Entity\News", cascade={"persist"})
     */
    protected $news;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $add_global;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sort;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_news;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->news = new ArrayCollection();
        $this->add_global = false;
        $this->sort = "date_start desc";
        $this->sort_news = 0;
    }

    /**
     * Set add_global
     *
     * @param boolean $add_global
     *
     * @return self
     */
    public function setAddGlobal($add_global)
    {
        $this->add_global = $add_global;

        return $this;
    }

    /**
     * Get add_global
     *
     * @return boolean $add_global
     */
    public function getAddGlobal()
    {
        return $this->add_global;
    }

    /**
     * Set sort
     *
     * @param string $sort
     *
     * @return self
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string $sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Get sort field
     *
     * @return string $sort
     */
    public function getSortField()
    {
        $sort = explode(" ", $this->sort);

        return $sort[0];
    }

    /**
     * Get sort order
     *
     * @return string $sort
     */
    public function getSortOrder()
    {
        $sort = explode(" ", $this->sort);

        return isset($sort[1]) ? $sort[1] : '';
    }

    /**
     * Get news
     *
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set news
     *
     * @param ArrayCollection $news
     *
     * @return $this
     */
    public function setNews(ArrayCollection $news)
    {
        $this->resetNews();
        foreach ($news as $new) {
            $new->addNewsgroup($this);
        }
        $this->news = $news;

        return $this;
    }

    /**
     * Add news
     *
     * @param \Fhm\NewsBundle\Entity\News $news
     *
     * @return $this
     */
    public function addNews(\Fhm\NewsBundle\Entity\News $news)
    {
        if (!$this->news->contains($news)) {
            $this->news->add($news);
            $news->addNewsgroup($this);
        }

        return $this;
    }

    /**
     * Remove news
     *
     * @param \Fhm\NewsBundle\Entity\News $news
     *
     * @return $this
     */
    public function removeNews(\Fhm\NewsBundle\Entity\News $news)
    {
        if ($this->news->contains($news)) {
            $this->news->removeElement($news);
            $news->removeNewsgroup($this);
        }

        return $this;
    }

    /**
     * Delete news
     *
     * @return $this
     */
    public function resetNews()
    {
        foreach ($this->news as $news) {
            $news->removeNewsgroup($this);
        }
        $this->news = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_news = $this->news->count();

//        return parent::sortUpdate();
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetNews();

//        return parent::preRemove();
    }
}