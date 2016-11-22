<?php
namespace Fhm\MediaBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Local
 *
 * @package Fhm\MediaBundle\Services
 */
class Local extends Controller
{
    protected $container;
    protected $document;
    private $files;
    private $file;
    private $path;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->files     = $this->_filesInit($this->getParameters('files', 'fhm_media'));
        $this->file      = null;
        // Path
        $this->path                = new \stdClass();
        $this->path->root          = $container->get('kernel')->getRootDir() . '/../';
        $this->path->origin        = 'media/';
        $this->path->media         = '/datas/media/';
        $this->path->web           = 'web' . $this->path->media;
        $this->path->watermark     = 'watermark.png';
        $this->path->files         = '';
        $this->path->fullWeb       = '';
        $this->path->fullOrigin    = $this->path->root . $this->path->origin;
        $this->path->fullWatermark = $this->path->root . 'web/images/common/' . $this->path->watermark;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param $document
     *
     * @return $this
     */
    public function setDocument($document)
    {
        $this->document      = $document;
        $this->file          = $document->getFile();
        $this->path->files   = $document->getId() . '/';
        $this->path->fullWeb = $this->path->root . $this->path->web . $this->path->files;

        return $this;
    }

    /**
     * @param $datas
     *
     * @return $this
     */
    public function setWatermark($datas)
    {
        if($datas)
        {
            foreach($datas as $name => $value)
            {
                $this->files[$name]['watermark'] = true;
            }
        }

        return $this;
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return \stdClass
     */
    public function getPathWeb()
    {
        return $this->getPathFile('origin', "default");
    }

    /**
     * @param $format
     * @param $default
     *
     * @return string
     */
    public function getPathFile($format, $default)
    {
        if($default === "default")
        {
            $default = file_exists('../web/images/default.jpg') ? '/images/default.jpg' : $default;
            $default = file_exists('../web/images/default.png') ? '/images/default.png' : $default;
        }
        if($this->document->getType() == 'image')
        {
            $file = $this->path->media . $this->document->getId() . '/' . $format . '.' . $this->document->getExtension();

            return ($file && file_exists('../web' . $file)) ? $file : $default;
        }
        else
        {
            $file = $this->path->media . $this->document->getId() . '/' . $this->document->getAlias() . '.' . $this->document->getExtension();

            return ($file && file_exists('../web' . $file)) ? $file : '#';
        }
    }

    /**
     * @param string $filename
     *
     * @return Response
     */
    public function download($filename = '')
    {
        $response = new Response();
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', $this->document->getMimeType());
        $response->headers->set('Content-Disposition', 'attachment; filename="' . ($filename ? $filename : $this->document->getName()) . '"');
        $response->setContent(file_get_contents($this->path->fullOrigin . $this->document->getId()));

        return $response;
    }

    /**
     * Execute
     */
    public function execute()
    {
        $this->_upload();
        $this->_tag();

        return $this;
    }

    /**
     * Generate Image
     */
    public function generateImage()
    {
        $this->_clearFolder($this->path->fullWeb);
        $this->_generateImage();

        return $this;
    }

    /**
     * Generate Tag
     */
    public function generateTag()
    {
        $this->_tag();

        return $this;
    }

    /**
     * Tag Root
     */
    public function tagRoot($root = "")
    {
        if($root === '&user')
        {
            $root      = $this->getUser()->getUsername();
            $tagParent = $this->dmRepository('FhmMediaBundle:MediaTag')->getByAlias('users');
            $tag       = $this->dmRepository('FhmMediaBundle:MediaTag')->getByAlias($root);
            if(!$tagParent)
            {
                $tagParent = new \Fhm\MediaBundle\Document\MediaTag();
                $tagParent->setName('users');
                $tagParent->setAlias('users');
                $tagParent->setActive(true);
                $tagParent->setPrivate(true);
                $this->dmPersist($tagParent);
            }
            if(!$tag)
            {
                $tag = new \Fhm\MediaBundle\Document\MediaTag();
                $tag->setName($root);
                $tag->setAlias($root);
                $tag->setParent($tagParent);
                $tag->setActive(true);
                $tag->setPrivate(true);
                $this->dmPersist($tag);
            }

            return $tag->getId();
        }
        if($root)
        {
            $tag = $this->dmRepository('FhmMediaBundle:MediaTag')->getById($root);
            $tag = ($tag) ? $tag : $this->dmRepository('FhmMediaBundle:MediaTag')->getByAlias($root);
            $tag = ($tag) ? $tag : $this->dmRepository('FhmMediaBundle:MediaTag')->getByName($root);
            if(!$tag)
            {
                $tag = new \Fhm\MediaBundle\Document\MediaTag();
                $tag->setName($root);
                $tag->setAlias($root);
                $tag->setActive(true);
                $this->dmPersist($tag);
            }

            return $tag->getId();
        }

        return $root;
    }

    /**
     * Remove
     */
    public function remove()
    {
        $files = array_diff(scandir($this->path->fullWeb), array('.', '..'));
        foreach($files as $file)
        {
            unlink($this->path->fullWeb . $file);
        }
        rmdir($this->path->fullWeb);
        if($this->document->getType() == 'image')
        {
            unlink($this->path->fullOrigin . $this->document->getId());
        }

        return;
    }

    /**
     * Upload
     */
    private function _upload()
    {
        if($this->file)
        {
            if(!is_dir($this->path->fullOrigin))
            {
                mkdir($this->path->fullOrigin, 0777, true);
            }
            if(!is_dir($this->path->fullWeb))
            {
                mkdir($this->path->fullWeb, 0777, true);
            }
        }
        // Image
        if($this->document->getType() == 'image')
        {
            $this->_uploadImage();
            $this->_generateImage();
        }
        // Other
        else
        {
            $this->_uploadFile();
        }
    }

    /**
     * Upload image
     */
    private function _uploadImage()
    {
        if($this->file && $this->document->getType() == 'image')
        {
            $this->file->move($this->path->fullOrigin, $this->document->getId());
        }
    }

    /**
     * Upload file
     */
    private function _uploadFile()
    {
        if($this->file && $this->document->getType() != 'image')
        {
            $this->file->move($this->path->fullWeb, $this->document->getAlias() . '.' . $this->document->getExtension());
        }
    }

    /**
     * Generate image
     */
    private function _generateImage()
    {
        if($this->document->getType() == 'image')
        {
            // Copy image
            $source1          = $this->path->fullWeb . 'tmp1.' . $this->document->getExtension();
            $source2          = $this->path->fullWeb . 'tmp2.' . $this->document->getExtension();
            $originalFileName = $this->path->fullWeb . $this->document->getAlias() . '.' . $this->document->getExtension();
            copy($this->path->fullOrigin . $this->document->getId(), $originalFileName);
            copy($this->path->fullOrigin . $this->document->getId(), $source1);
            copy($this->path->fullOrigin . $this->document->getId(), $source2);
            // Initialization
            $sizeSource    = getimagesize($source1);
            $sizeWatermark = getimagesize($this->path->fullWatermark);
            $function1     = $this->_getImagecreatefrom($this->document->getExtension());
            $function2     = $this->_getImage($this->document->getExtension());
            // Resize watermarker
            $watermarkPercent = $sizeWatermark[0] > $sizeWatermark[1] ? $sizeWatermark[0] * 100 / ($sizeSource[0] - 40) : $sizeWatermark[1] * 100 / ($sizeSource[1] - 40);
            $watermarkWidth   = 100 * $sizeWatermark[0] / $watermarkPercent;
            $watermarkHeight  = 100 * $sizeWatermark[1] / $watermarkPercent;
            $watermarkX       = ($sizeSource[0] - $watermarkWidth) / 2;
            $watermarkY       = ($sizeSource[1] - $watermarkHeight) / 2;
            $watermark        = imagecreatetruecolor($watermarkWidth, $watermarkHeight);
            imagealphablending($watermark, false);
            imagesavealpha($watermark, true);
            $transparent = imagecolorallocatealpha($watermark, 255, 255, 255, 127);
            imagefilledrectangle($watermark, 0, 0, $sizeWatermark[0], $sizeWatermark[1], $transparent);
            imagecopyresampled($watermark, imagecreatefrompng($this->path->fullWatermark), 0, 0, 0, 0, $watermarkWidth, $watermarkHeight, $sizeWatermark[0], $sizeWatermark[1]);
            imagepng($watermark, $this->path->fullWeb . $this->path->watermark);
            // Add watermarker
            $object = call_user_func($function1, $source2);
            imagecopy($object, $watermark, $watermarkX, $watermarkY, 0, 0, $watermarkWidth, $watermarkHeight);
            call_user_func($function2, $object, $source2);
            imagedestroy($object);
            imagedestroy($watermark);
            // Generate images file
            foreach($this->files as $file)
            {
                // Copy image
                copy($file['watermark'] ? $source2 : $source1, $this->path->fullWeb . $file['name'] . '.' . $this->document->getExtension());
                // Square
                if($file['width'] > 0 && $file['height'] === null)
                {
                    $size           = $file['width'];
                    $file['width']  = $sizeSource[0] >= $sizeSource[1] ? $size : 0;
                    $file['height'] = $sizeSource[0] < $sizeSource[1] ? $size : 0;
                }
                // Resize
                if($file['width'] + $file['height'] > 0)
                {
                    if($file['width'] > 0 && $file['height'] > 0)
                    {
                        $percent = ($sizeSource[0] / $file['width'] > $sizeSource[1] / $file['height']) ? $file['width'] * 100 / $sizeSource[0] : $file['height'] * 100 / $sizeSource[1];
                    }
                    else
                    {
                        $percent = $file['width'] >= $file['height'] ? $file['width'] * 100 / $sizeSource[0] : $file['height'] * 100 / $sizeSource[1];
                    }
                    $width   = $percent * $sizeSource[0] / 100;
                    $height  = $percent * $sizeSource[1] / 100;
                    $offsetX = $file['width'] > $width ? ($file['width'] - $width) / 2 : 0;
                    $offsetY = $file['height'] > $height ? ($file['height'] - $height) / 2 : 0;
                    $object  = imagecreatetruecolor($file['width'] == 0 ? $width : $file['width'], $file['height'] == 0 ? $height : $file['height']);
                    imagealphablending($object, false);
                    imagesavealpha($object, true);
                    $transparent = imagecolorallocatealpha($object, 255, 255, 255, 127);
                    imagefilledrectangle($object, 0, 0, $file['width'] == 0 ? $width : $file['width'], $file['height'] == 0 ? $height : $file['height'], $transparent);
                    imagecopyresampled($object, call_user_func($function1, $this->path->fullWeb . $file['name'] . '.' . $this->document->getExtension()), $offsetX, $offsetY, 0, 0, $width, $height, $sizeSource[0], $sizeSource[1]);
                    call_user_func($function2, $object, $this->path->fullWeb . $file['name'] . '.' . $this->document->getExtension());
                    imagedestroy($object);
                }
            }
            // Delete temporary image
            unlink($source1);
            unlink($source2);
            unlink($this->path->fullWeb . $this->path->watermark);
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function _tag()
    {
        $tagType      = $this->dmRepository('FhmMediaBundle:MediaTag')->getByAlias($this->document->getType());
        $tagExtension = $this->dmRepository('FhmMediaBundle:MediaTag')->getByAlias($this->document->getExtension());
        if(!$tagType)
        {
            $tagType = new \Fhm\MediaBundle\Document\MediaTag();
            $tagType->setName($this->document->getType());
            $tagType->setAlias($this->document->getType());
            $tagType->setActive(true);
            $this->dmPersist($tagType);
        }
        if(!$tagExtension)
        {
            $tagExtension = new \Fhm\MediaBundle\Document\MediaTag();
            $tagExtension->setName($this->document->getExtension());
            $tagExtension->setAlias($this->document->getExtension());
            $tagExtension->setParent($tagType);
            $tagExtension->setActive(true);
            $this->dmPersist($tagExtension);
        }
        $this->document->addTag($tagType);
        $this->document->addTag($tagExtension);
        $this->dmPersist($this->document);

        return $this;
    }

    /**
     * @param $folder
     *
     * @return $this
     */
    private function _clearFolder($folder)
    {
        $dir = opendir($folder);
        while($file = readdir($dir))
        {
            if($file != "." && $file != "..")
            {
                unlink($folder . $file);
            }
        }
        closedir($dir);

        return $this;
    }

    /**
     * @param $files
     *
     * @return array
     */
    private function _filesInit($files)
    {
        $list = array();
        foreach($files as $name => $size)
        {
            $size        = explode(':', $size);
            $list[$name] = array(
                'name'      => $name,
                'watermark' => false,
                'width'     => $size[0],
                'height'    => isset($size[1]) ? $size[1] : null
            );
        }

        return $list;
    }

    /**
     * @param $extension
     *
     * @return string
     */
    private function _getImagecreatefrom($extension)
    {
        if($extension == 'jpg')
        {
            $extension = 'jpeg';
        }

        return 'imagecreatefrom' . $extension;
    }

    /**
     * @param $extension
     *
     * @return string
     */
    private function _getImage($extension)
    {
        if($extension == 'jpg')
        {
            $extension = 'jpeg';
        }

        return 'image' . $extension;
    }
}