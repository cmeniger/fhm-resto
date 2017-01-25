<?php
namespace Fhm\NewsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NewsHistoric
 * @MongoDB\Document(repositoryClass="Fhm\NewsBundle\Document\Repository\NewsRepository")
 */
class NewsHistoric extends News
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Historic merge
     */
    public function historicMerge($dm, $document)
    {
        // ReferenceOne
        $this->image   = $document->getImage() ? $dm->getRepository('FhmMediaBundle:Media')->find($document->getImage()->getId()) : null;
        $this->gallery = $document->getGallery() ? $dm->getRepository('FhmGalleryBundle:Gallery')->find($document->getGallery()->getId()) : null;
        $this->author  = $document->getAuthor() ? $dm->getRepository('FhmUserBundle:User')->find($document->getAuthor()->getId()) : null;
        // ReferenceMany
        foreach($document->getNewsgroups() as $newsgroup)
        {
            $this->newsgroups->add($newsgroup);
        }
        // Rest
        $this->title      = $document->getTitle();
        $this->subtitle   = $document->getSubtitle();
        $this->resume     = $document->getResume();
        $this->content    = $document->getContent();
        $this->date_start = $document->getDateStart();
        $this->date_end   = $document->getDateEnd();

        return parent::historicMerge($dm, $document);
    }

    /**
     * Difference
     */
    public function historicDifference()
    {
        $count = 0;
        if($this->historic_parent)
        {
            $count += $this->getImage() != $this->getHistoricParent()->getImage() ? 1 : 0;
            $count += $this->getGallery() != $this->getHistoricParent()->getGallery() ? 1 : 0;
            $count += $this->getAuthor() != $this->getHistoricParent()->getAuthor() ? 1 : 0;
            $count += $this->getNewsgroups()->toArray() != $this->getHistoricParent()->getNewsgroups()->toArray() ? 1 : 0;
            $count += $this->getTitle() != $this->getHistoricParent()->getTitle() ? 1 : 0;
            $count += $this->getSubtitle() != $this->getHistoricParent()->getSubtitle() ? 1 : 0;
            $count += $this->getResume() != $this->getHistoricParent()->getResume() ? 1 : 0;
            $count += $this->getContent() != $this->getHistoricParent()->getContent() ? 1 : 0;
            $count += $this->getDateStart() != $this->getHistoricParent()->getDateStart() ? 1 : 0;
            $count += $this->getDateEnd() != $this->getHistoricParent()->getDateEnd() ? 1 : 0;
            $count += $this->getDelete() != $this->getHistoricParent()->getDelete() ? 1 : 0;
            $count += $this->getActive() != $this->getHistoricParent()->getActive() ? 1 : 0;
            $count += $this->getShare() != $this->getHistoricParent()->getShare() ? 1 : 0;
            $count += $this->getGlobal() != $this->getHistoricParent()->getGlobal() ? 1 : 0;
            $count += $this->getGrouping() != $this->getHistoricParent()->getGrouping() ? 1 : 0;
            $count += $this->getLanguages() != $this->getHistoricParent()->getLanguages() ? 1 : 0;
            $count += $this->getSeoTitle() != $this->getHistoricParent()->getSeoTitle() ? 1 : 0;
            $count += $this->getSeoDescription() != $this->getHistoricParent()->getSeoDescription() ? 1 : 0;
            $count += $this->getSeoKeywords() != $this->getHistoricParent()->getSeoKeywords() ? 1 : 0;
        }

        return $count;
    }
}