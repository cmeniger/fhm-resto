<?php
namespace Project\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Project\ProductBundle\Entity\Repository\ProductIngredientRepository")
 * @ORM\Table()
 */
class ProductIngredient extends \Fhm\FhmBundle\Entity\Fhm
{

    /**
     * @ORM\OneToOne(targetEntity="ProductIngredient")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $route;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->parent = null;
        $this->route  = '';
        $this->color  = null;
    }

    /**
     * @return null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param null $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

}