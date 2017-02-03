<?php
namespace Fhm\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fhm\FhmBundle\Entity\Fhm;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Fhm\MediaBundle\Entity\Repository\MediaRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 */
class Media extends Fhm
{
    /**
     * @ORM\ManyToMany(targetEntity="MediaTag", cascade={"persist"}, mappedBy="medias")
     */
    protected $tags;

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $filename;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $path;

    /**
     * @var
     */
    protected $temp;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $mimeType;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $extension;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $size;

    /**
     * @ORM\Column(type="array")
     */
    protected $watermark;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $private;



    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->tags = new ArrayCollection();
        $this->private = false;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string $mimeType
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get watermark
     *
     * @return string $watermark
     */
    public function getWatermark()
    {
        return $this->watermark;
    }

    /**
     * Set watermark
     *
     * @param array $watermark
     *
     * @return $this
     */
    public function setWatermark(array $watermark)
    {
        $this->watermark = $watermark;

        return $this;
    }

    /**
     * Has watermark
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasWatermark($name)
    {
        return isset($this->watermark[$name]) ? true : false;
    }

    /**
     * Get filename
     *
     * @return string $filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string $extension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return self
     */
    public function setExtension($extension)
    {
        $this->extension = strtolower($extension);

        return $this;
    }

    /**
     * Get size
     *
     * @return string $size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get file
     *
     * @return string $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set private
     *
     * @param boolean $private
     *
     * @return self
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean $private
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param ArrayCollection $tags
     *
     * @return self
     */
    public function setTags(ArrayCollection $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Add tag
     *
     * @param \Fhm\MediaBundle\Entity\MediaTag $tag
     *
     * @return $this
     */
    public function addTag(\Fhm\MediaBundle\Entity\MediaTag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addMedia($this);
        }

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Fhm\MediaBundle\Entity\MediaTag $tag
     *
     * @return $this
     */
    public function removeTag(\Fhm\MediaBundle\Entity\MediaTag $tag)
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if (isset($this->path)) {
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return 'datas/media';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null === $this->file) {
            return;
        }

        if ($this->path != $this->file->getClientOriginalName()) {
            $this->path = $this->file->getClientOriginalName();
        }
        $ext = explode('/', $this->file->getClientMimeType());
        $this->setExtension($this->file->getClientOriginalExtension());
        $this->setFilename($this->file->getClientOriginalName());
        $this->setSize($this->file->getClientSize());
        $this->setType($ext[0]);
        $this->setMimeType($this->file->getClientMimeType());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        $filename = $this->file->getClientOriginalName();
        if (!file_exists($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir(), 0775, true);
        }
        $this->file->move($this->getUploadRootDir(), $filename);
        $this->file = null;
    }
}