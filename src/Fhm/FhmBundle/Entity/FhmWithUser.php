<?php
namespace Fhm\FhmBundle\Entity;

use FOS\UserBundle\Model\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fhm
 * @ORM\HasLifecycleCallbacks
 */
class FhmWithUser extends User
{
    /**
     * @ORM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_create;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     */
    protected $date_update;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\UserBundle\Entity\User", nullable=true, cascade={"persist"})
     */
    protected $user_create;

    /**
     * @ORM\OneToOne(targetEntity="Fhm\UserBundle\Entity\User", nullable=true, cascade={"persist"})
     */
    protected $user_update;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $delete;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $share;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $global;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $alias;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0)
     */
    protected $order;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max = 50)
     */
    protected $seo_title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $seo_keywords;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max = 150)
     */
    protected $seo_description;

    /**
     * @ORM\OneToOne(nullable=true, cascade={"persist"})
     */
    protected $historic_parent;

    /**
     * @ORM\OneToMany(nullable=true, cascade={"persist"})
     */
    protected $historic_sons;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->active = false;
        $this->delete = false;
        $this->share = false;
        $this->global = false;
        $this->order = 0;
        $this->alias = null;
        $this->historic_sons = new ArrayCollection();
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
     * Set date create
     *
     * @param \datetime $dateCreate
     *
     * @return self
     */
    public function setDateCreate($dateCreate)
    {
        $this->date_create = $dateCreate;

        return $this;
    }

    /**
     * Get date create
     *
     * @return \datetime $dateCreate
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * Set date update
     *
     * @param \datetime $dateUpdate
     *
     * @return self
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->date_update = $dateUpdate;

        return $this;
    }

    /**
     * Get date update
     *
     * @return \datetime $dateUpdate
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * Set user create
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return self
     */
    public function setUserCreate($user)
    {
        $this->user_create = $user;

        return $this;
    }

    /**
     * Get user create
     *
     * @return \Fhm\UserBundle\Entity\User $user
     */
    public function getUserCreate()
    {
        return $this->user_create;
    }

    /**
     * Set user update
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return self
     */
    public function setUserUpdate($user)
    {
        $this->user_update = $user;

        return $this;
    }

    /**
     * Get user update
     *
     * @return \Fhm\UserBundle\Entity\User $user
     */
    public function getUserUpdate()
    {
        return $this->user_update;
    }

    /**
     * Set delete
     *
     * @param boolean $delete
     *
     * @return self
     */
    public function setDelete($delete)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * Get delete
     *
     * @return boolean $delete
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean $active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set share
     *
     * @param boolean $share
     *
     * @return self
     */
    public function setShare($share)
    {
        $this->share = $share;

        return $this;
    }

    /**
     * Get share
     *
     * @return boolean $share
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Set global
     *
     * @param boolean $global
     *
     * @return self
     */
    public function setGlobal($global)
    {
        $this->global = $global;

        return $this;
    }

    /**
     * Get global
     *
     * @return boolean $global
     */
    public function getGlobal()
    {
        return $this->global;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return self
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Get order
     *
     * @return string $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param string $order
     *
     * @return self
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
     * Get historic sons
     *
     * @return mixed
     */
    public function getHistoricSons()
    {
        return $this->historic_sons;
    }

    /**
     * Set historic sons
     *
     * @param ArrayCollection $sons
     *
     * @return $this
     */
    public function setHistoricSons(ArrayCollection $sons)
    {
        $this->resetHistoricSons();
        foreach ($sons as $son) {
            $son->setHistoricParent($this);
        }
        $this->historic_sons = $sons;

        return $this;
    }

    /**
     * Add historic son
     *
     * @param $son
     *
     * @return $this
     */
    public function addHistoricSon($son)
    {
        if (!$this->historic_sons->contains($son)) {
            $this->historic_sons->add($son);
        }

        return $this;
    }

    /**
     * Remove historic son
     *
     * @param $son
     *
     * @return $this
     */
    public function removeHistoricSon($son)
    {
        if ($this->historic_sons->contains($son)) {
            $this->historic_sons->removeElement($son);
        }

        return $this;
    }

    /**
     * Reset historic sons
     *
     * @return $this
     */
    public function resetHistoricSons()
    {
        foreach ($this->historic_sons as $son) {
            $son->removeHistoricParent($this);
        }
        $this->historic_sons = new ArrayCollection();

        return $this;
    }

    /**
     * Get historic parent
     *
     * @return mixed
     */
    public function getHistoricParent()
    {
        return $this->historic_parent;
    }

    /**
     * Set historic parent
     *
     * @param $parent
     *
     * @return self
     */
    public function setHistoricParent($parent)
    {
        $this->removeHistoricParent();
        $this->historic_parent = $parent;

        return $this;
    }

    /**
     * Remove historic parent
     *
     * @return self
     */
    public function removeHistoricParent()
    {
        if ($this->historic_parent) {
            $this->historic_parent->removeHistoricSon($this);
        }
        $this->historic_parent = null;

        return $this;
    }

    /**
     * Historic merge
     */
    public function historicMerge($dm, $document)
    {
        // ReferenceOne
        $this->user_create = $document->getUserCreate() ? $dm->getRepository('FhmUserBundle:User')->find(
            $document->getUserCreate()->getId()
        ) : null;
        $this->user_update = $document->getUserUpdate() ? $dm->getRepository('FhmUserBundle:User')->find(
            $document->getUserUpdate()->getId()
        ) : null;
        // Rest
        $this->name = $document->getName();
        $this->alias = $document->getAlias();
        $this->description = $document->getDescription();
        $this->delete = $document->getDelete();
        $this->active = $document->getActive();
        $this->share = $document->getShare();
        $this->global = $document->getGlobal();
        $this->order = $document->getOrder();
        $this->date_create = $document->getDateCreate();
        $this->date_update = $document->getDateUpdate();
        $this->seo_title = $document->getSeoTitle();
        $this->seo_description = $document->getSeoDescription();
        $this->seo_keywords = $document->getSeoKeywords();

        return $this;
    }

    /**
     * Historic difference
     */
    public function historicDifference()
    {
        $count = 0;
        if ($this->historic_parent) {
            $count += $this->name != $this->historic_parent->name ? 1 : 0;
            $count += $this->alias != $this->historic_parent->alias ? 1 : 0;
            $count += $this->description != $this->historic_parent->description ? 1 : 0;
            $count += $this->delete != $this->historic_parent->delete ? 1 : 0;
            $count += $this->active != $this->historic_parent->active ? 1 : 0;
            $count += $this->share != $this->historic_parent->share ? 1 : 0;
            $count += $this->global != $this->historic_parent->global ? 1 : 0;
            $count += $this->order != $this->historic_parent->order ? 1 : 0;
            $count += $this->seo_title != $this->historic_parent->seo_title ? 1 : 0;
            $count += $this->seo_description != $this->historic_parent->seo_description ? 1 : 0;
            $count += $this->seo_keywords != $this->historic_parent->seo_keywords ? 1 : 0;
        }

        return $count;
    }


    /**
     * @return bool
     */
    public function isEnable()
    {
        return ($this->active && !$this->delete) ? true : false;
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
            'name',
            'description',
            'alias',
            'date_create',
            'date_update',
            'delete',
            'active',
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
            utf8_decode($this->name),
            utf8_decode($this->description),
            utf8_decode($this->alias),
            ($this->date_create) ? $this->date_create->format('d/m/Y H:i:s') : '',
            ($this->date_update) ? $this->date_update->format('d/m/Y H:i:s') : '',
            $this->delete,
            $this->active,
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
        $this->name = (isset($data['name'])) ? $data['name'] : $this->name;
        $this->description = (isset($data['description'])) ? $data['description'] : $this->description;
        $this->alias = (isset($data['alias'])) ? $data['alias'] : $this->alias;

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        return $this;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetHistoricSons();

        return $this;
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemove()
    {
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setDateCreate(new \DateTime());
        $this->setDateUpdate(new \DateTime());
        $this->sortUpdate();

        return $this;
    }

    /**
     * @ORM\PostPersist()
     */
    public function postPersist()
    {
        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setDateUpdate(new \DateTime());
        $this->sortUpdate();

        return $this;
    }

    /**
     * @ORM\PostUpdate()
     */
    public function postUpdate()
    {
        return $this;
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        return $this;
    }
}