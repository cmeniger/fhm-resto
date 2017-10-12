<?php

namespace Project\DefaultBundle\Services;

use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Moderator
 *
 * @package Project\DefaultBundle\Services
 */
class Moderator
{
    private $tools;

    /**
     * Moderator constructor.
     *
     * @param Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
     * @param Request $request
     */
    public function menuSort(Request $request)
    {
        $id   = $request->get('master');
        $list = json_decode($request->get('list'));
        $this->__menuSort($id, $list);
    }

    /**
     * @param $id
     */
    public function menuDelete($id)
    {
        $object = $this->tools->dmRepository('FhmFhmBundle:Menu')->find($id);
        if($object)
        {
            $delete = ($object->getDelete()) ? true : false;
            $this->__menuDelete($id, $object, $delete);
            if($delete)
            {
                $this->tools->dmRemove($object);
            }
            else
            {
                $object->setDelete(true);
                $this->tools->dmPersist($object);
            }
        }
    }

    /**
     * @param $id
     */
    public function sliderDelete($id)
    {
        $object = $this->tools->dmRepository('FhmSliderBundle:SliderItem')->find($id);
        if($object)
        {
            if($object->getDelete())
            {
                $this->tools->dmRemove($object);
            }
            else
            {
                $object->setDelete(true);
                $this->tools->dmPersist($object);
            }
        }
    }

    /**
     * @param $id
     */
    public function galleryDelete($id)
    {
        $object = $this->tools->dmRepository('FhmGalleryBundle:GalleryItem')->find($id);
        $object = $object ? $object : $this->tools->dmRepository('FhmGalleryBundle:GalleryVideo')->find($id);
        if($object)
        {
            if($object->getDelete())
            {
                $this->tools->dmRemove($object);
            }
            else
            {
                $object->setDelete(true);
                $this->tools->dmPersist($object);
            }
        }
    }

    /**
     * @param $id
     */
    public function partnerDelete($id)
    {
        $object = $this->tools->dmRepository('FhmPartnerBundle:Partner')->find($id);
        if($object)
        {
            if($object->getDelete())
            {
                $this->tools->dmRemove($object);
            }
            else
            {
                $object->setDelete(true);
                $this->tools->dmPersist($object);
            }
        }
    }

    /**
     * @param $parent
     * @param $list
     *
     * @return $this
     */
    private function __menuSort($parent, $list)
    {
        $order        = 1;
        $objectParent = $this->tools->dmRepository('FhmFhmBundle:Menu')->find($parent);
        $tabChilds    = $objectParent->getChilds();
        $objectParent->setChilds(new ArrayCollection());
        foreach($list as $obj)
        {
            $object = $this->tools->dmRepository('FhmFhmBundle:Menu')->find($obj->id);
            if(isset($obj->children))
            {
                $this->__menuSort($obj->id, $obj->children);
            }
            // change order in parent
            foreach($tabChilds as $key => $son)
            {
                if($son->getId() == $obj->id)
                {
                    $objectParent->addChild($son);
                }
            }
            //add new child in parent and remove child in old parent
            if($parent == $objectParent->getId())
            {
                $objectOldParent = $this->tools->dmRepository('FhmFhmBundle:Menu')->find(
                    $object->getParent()
                );
                $objectOldParent->removeChild($object);
                $objectParent->addChild($object);
                $this->tools->dmPersist($objectOldParent);
            }
            $object->setOrder($order);
            $object->setParent($parent);
            $this->tools->dmPersist($object);
            $this->tools->dmPersist($objectParent);
            $order++;
        }

        return $this;
    }

    /**
     * @param $idp
     * @param $object
     * @param $delete
     *
     * @return $this
     */
    private function __menuDelete($idp, $object, $delete)
    {
        $sons = $this->tools->dmRepository('FhmFhmBundle:Menu')->getSons($idp);
        // remove childs form parent
        if($delete && $object->getParent() != '0')
        {
            $objectParent = $this->tools->dmRepository('FhmFhmBundle:Menu')->find($object->getParent());
            $objectParent->removeChild($object);
            $this->tools->dmPersist($objectParent);
        }
        // delete all childs
        foreach($sons as $son)
        {
            $this->__menuDelete($son->getId(), $son, $delete);
            if($delete)
            {
                $this->tools->dmRemove($son);
            }
            else
            {
                $son->setDelete(true);
                $this->tools->dmPersist($son);
            }
        }

        return $this;
    }
}