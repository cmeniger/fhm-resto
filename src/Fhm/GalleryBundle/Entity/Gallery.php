<?php
namespace Fhm\GalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Gallery extends Fhm
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
     * @ORM\OneToOne(targetEntity="Media")
     */
    protected $image;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $add_global_item;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $add_global_video;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $order_item;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $order_video;

    /**
     * @ORM\OneToMany(targetEntity="GalleryItem")
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="GalleryVideo")
     */
    protected $videos;

    /**
     * @ORM\OneToMany(targetEntity="GalleryAlbum")
     */
    protected $albums;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_item;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_video;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_album;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->items            = new ArrayCollection();
        $this->videos           = new ArrayCollection();
        $this->albums           = new ArrayCollection();
        $this->add_global_item  = false;
        $this->add_global_video = false;
        $this->order_item       = "order desc";
        $this->order_video      = "order desc";
        $this->sort_item        = 0;
        $this->sort_video       = 0;
        $this->sort_album       = 0;
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
     * @return mixed
     */
    public function getAddGlobalItem()
    {
        return $this->add_global_item;
    }

    /**
     * @param $add_global_item
     *
     * @return $this
     */
    public function setAddGlobalItem($add_global_item)
    {
        $this->add_global_item = $add_global_item;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAddGlobalVideo()
    {
        return $this->add_global_video;
    }

    /**
     * @param $add_global_video
     *
     * @return $this
     */
    public function setAddGlobalVideo($add_global_video)
    {
        $this->add_global_video = $add_global_video;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderItem()
    {
        return $this->order_item;
    }

    /**
     * Get order item field
     *
     * @return string $sort
     */
    public function getOrderItemField()
    {
        $sort = explode(" ", $this->order_item);

        return $sort[0];
    }

    /**
     * Get order item order
     *
     * @return string $sort
     */
    public function getOrderItemOrder()
    {
        $sort = explode(" ", $this->order_item);

        return isset($sort[1]) ? $sort[1] : 'asc';
    }

    /**
     * @param $order_item
     *
     * @return $this
     */
    public function setOrderItem($order_item)
    {
        $this->order_item = $order_item;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderVideo()
    {
        return $this->order_video;
    }

    /**
     * Get order video field
     *
     * @return string $sort
     */
    public function getOrderVideoField()
    {
        $sort = explode(" ", $this->order_video);

        return $sort[0];
    }

    /**
     * Get order video order
     *
     * @return string $sort
     */
    public function getOrderVideoOrder()
    {
        $sort = explode(" ", $this->order_video);

        return isset($sort[1]) ? $sort[1] : 'asc';
    }

    /**
     * @param $order_video
     *
     * @return $this
     */
    public function setOrderVideo($order_video)
    {
        $this->order_video = $order_video;

        return $this;
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
        $this->sort_item = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string $sort
     */
    public function getSort()
    {
        return $this->sort_item;
    }

    /**
     * Get sort field
     *
     * @return string $sort
     */
    public function getSortField()
    {
        $sort = explode(" ", $this->sort_item);

        return $sort[0];
    }

    /**
     * Get sort order
     *
     * @return string $sort
     */
    public function getSortOrder()
    {
        $sort = explode(" ", $this->sort_item);

        return isset($sort[1]) ? $sort[1] : 'asc';
    }

    /**
     * Get items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set items
     *
     * @param ArrayCollection $items
     *
     * @return $this
     */
    public function setItems(ArrayCollection $items)
    {
        $this->resetItems();
        foreach($items as $item)
        {
            $item->addGallery($this);
        }
        $this->items = $items;

        return $this;
    }

    /**
     * Add item
     *
     * @param \Fhm\GalleryBundle\Entity\GalleryItem $item
     *
     * @return $this
     */
    public function addItem(\Fhm\GalleryBundle\Entity\GalleryItem $item)
    {
        if(!$this->items->contains($item))
        {
            $this->items->add($item);
            $item->addGallery($this);
        }

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Fhm\GalleryBundle\Entity\GalleryItem item
     *
     * @return $this
     */
    public function removeItem(\Fhm\GalleryBundle\Entity\GalleryItem $item)
    {
        if($this->items->contains($item))
        {
            $this->items->removeElement($item);
            $item->removeGallery($this);
        }

        return $this;
    }

    /**
     * Reset items
     *
     * @return $this
     */
    public function resetItems()
    {
        foreach($this->items as $item)
        {
            $item->removeGallery($this);
        }
        $this->items = new ArrayCollection();

        return $this;
    }

    /**
     * Get videos
     *
     * @return mixed
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set videos
     *
     * @param ArrayCollection $videos
     *
     * @return $this
     */
    public function setVideos(ArrayCollection $videos)
    {
        $this->resetVideos();
        foreach($videos as $video)
        {
            $video->addGallery($this);
        }
        $this->videos = $videos;

        return $this;
    }

    /**
     * Add video
     *
     * @param \Fhm\GalleryBundle\Entity\GalleryVideo $video
     *
     * @return $this
     */
    public function addVideo(\Fhm\GalleryBundle\Entity\GalleryVideo $video)
    {
        if(!$this->videos->contains($video))
        {
            $this->videos->add($video);
            $video->addGallery($this);
        }

        return $this;
    }

    /**
     * Remove video
     *
     * @param \Fhm\GalleryBundle\Entity\GalleryVideo video
     *
     * @return $this
     */
    public function removeVideo(\Fhm\GalleryBundle\Entity\GalleryVideo $video)
    {
        if($this->videos->contains($video))
        {
            $this->videos->removeElement($video);
            $video->removeGallery($this);
        }

        return $this;
    }

    /**
     * Reset videos
     *
     * @return $this
     */
    public function resetVideos()
    {
        foreach($this->videos as $video)
        {
            $video->removeGallery($this);
        }
        $this->videos = new ArrayCollection();

        return $this;
    }

    /**
     * Get albums
     *
     * @return mixed
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Set albums
     *
     * @param ArrayCollection $albums
     *
     * @return $this
     */
    public function setAlbums(ArrayCollection $albums)
    {
        $this->resetAlbums();
        foreach($albums as $album)
        {
            $album->addGallery($this);
        }
        $this->albums = $albums;

        return $this;
    }

    /**
     * Add album
     *
     * @param \Fhm\GalleryBundle\Entity\GalleryAlbum $album
     *
     * @return $this
     */
    public function addAlbum(\Fhm\GalleryBundle\Entity\GalleryAlbum $album)
    {
        if(!$this->albums->contains($album))
        {
            $this->albums->add($album);
            $album->addGallery($this);
        }

        return $this;
    }

    /**
     * Remove album
     *
     * @param \Fhm\GalleryBundle\Entity\GalleryAlbum album
     *
     * @return $this
     */
    public function removeAlbum(\Fhm\GalleryBundle\Entity\GalleryAlbum $album)
    {
        if($this->albums->contains($album))
        {
            $this->albums->removeElement($album);
            $album->removeGallery($this);
        }

        return $this;
    }

    /**
     * Reset albums
     *
     * @return $this
     */
    public function resetAlbums()
    {
        foreach($this->albums as $album)
        {
            $album->removeGallery($this);
        }
        $this->albums = new ArrayCollection();

        return $this;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_item  = $this->items->count();
        $this->sort_video = $this->videos->count();
        $this->sort_album = $this->albums->count();

        return parent::sortUpdate();
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function preRemove()
    {
        $this->resetAlbums();
        $this->resetItems();
        $this->resetVideos();

        return parent::preRemove();
    }
}