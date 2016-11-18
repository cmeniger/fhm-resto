<?php
namespace Fhm\PageBundle\Document;

use Fhm\FhmBundle\Document\Fhm as FhmFhm;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 * @MongoDB\Document(repositoryClass="Fhm\PageBundle\Repository\PageRepository")
 */
class Page extends FhmFhm
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @MongoDB\Field(type="string")
     */
    protected $parent = 0;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $module = array();

    /**
     * Get parent
     *
     * @return string $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param string $parent
     *
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }


    /**
     * Get module
     *
     * @return string $module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * set module
     *
     * @param $module
     * @return  $this
     */
    public function setModule($module)
    {
        $this->module=$module;
        return $this;
    }

    /**
     * add module
     *
     * @param array $module
     *
     * @return self
     */
    public function addModule($module)
    {
        array_push($this->module,$module);
        return $this;
    }


}