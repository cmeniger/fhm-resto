<?php
namespace Fhm\ArticleBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 * @MongoDB\Document(repositoryClass="Fhm\ArticleBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_create;

    /**
     * @MongoDB\Field(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_update;

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
    protected $author;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $resume;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $delete;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $active;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $share;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $global;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $description;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $alias;

    /**
     * @MongoDB\Field(type="int")
     * @Assert\Range(min = 0)
     */
    protected $order;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Length(max = 50)
     */
    protected $seo_title;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $seo_keywords;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Length(max = 150)
     */
    protected $seo_description;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->active        = false;
        $this->delete        = false;
        $this->share         = false;
        $this->global        = false;
        $this->order         = 0;
        $this->alias         = null;
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * @return mixed
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * @param $date_create
     *
     * @return $this
     */
    public function setDateCreate($date_create)
    {
        $this->date_create = $date_create;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * @param $date_update
     *
     * @return $this
     */
    public function setDateUpdate($date_update)
    {
        $this->date_update = $date_update;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * @param $delete
     *
     * @return $this
     */
    public function setDelete($delete)
    {
        $this->delete = $delete;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * @param $share
     *
     * @return $this
     */
    public function setShare($share)
    {
        $this->share = $share;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGlobal()
    {
        return $this->global;
    }

    /**
     * @param $global
     *
     * @return $this
     */
    public function setGlobal($global)
    {
        $this->global = $global;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeoTitle()
    {
        return $this->seo_title;
    }

    /**
     * @param $seo_title
     *
     * @return $this
     */
    public function setSeoTitle($seo_title)
    {
        $this->seo_title = $seo_title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeoKeywords()
    {
        return $this->seo_keywords;
    }

    /**
     * @param $seo_keywords
     *
     * @return $this
     */
    public function setSeoKeywords($seo_keywords)
    {
        $this->seo_keywords = $seo_keywords;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeoDescription()
    {
        return $this->seo_description;
    }

    /**
     * @param $seo_description
     *
     * @return $this
     */
    public function setSeoDescription($seo_description)
    {
        $this->seo_description = $seo_description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }



}