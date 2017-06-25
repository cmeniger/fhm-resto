<?php

namespace Fhm\MediaBundle\Services;

use Fhm\FhmBundle\Manager\FhmObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Local
 *
 * @package Fhm\MediaBundle\Services
 */
class Local
{
    private $files;
    private $file = null;
    private $path;

    protected $model;
    protected $rootDir;
    protected $objectManager;
    protected $tokenStorage;

    /**
     * Local constructor.
     *
     * @param FhmObjectManager $manager
     * @param TokenStorage     $tokenStorage
     * @param array            $fhmMedia
     * @param                  $kernelRootDir
     */
    public function __construct(FhmObjectManager $manager, TokenStorage $tokenStorage, array $fhmMedia, $kernelRootDir)
    {
        $this->tokenStorage  = $tokenStorage;
        $this->objectManager = $manager;
        $this->rootDir       = $kernelRootDir;
        $this->files         = $this->filesInit(isset($fhmMedia['files']) ? $fhmMedia['files'] : []);
        // Path
        $this->path                = new \stdClass();
        $this->path->root          = $kernelRootDir . '/../';
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
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        if($model)
        {
            $this->model         = $model;
            $this->file          = $model->getFile();
            $this->path->files   = $model->getId() . '/';
            $this->path->fullWeb = $this->path->root . $this->path->web . $this->path->files;
        }

        return $this;
    }

    /**
     * Execute
     */
    public function execute()
    {
        if($this->model->getFile() instanceof UploadedFile)
        {
            $this->upload();
            $this->tag();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isModelSet()
    {
        $type = $this->objectManager->getCurrentModelName('FhmMediaBundle:Media');

        return $this->model instanceof $type;
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
            $default = file_exists($this->path->root . 'web/images/default.jpg') ? '/images/default.jpg' : $default;
            $default = file_exists($this->path->root . 'web/images/default.png') ? '/images/default.png' : $default;
        }
        if($this->isModelSet())
        {
            if($this->model->getType() == 'image')
            {
                $file = $this->path->media . $this->model->getId() . '/' . $format . '.' . $this->model->getExtension();

                return ($file && file_exists($this->path->root . 'web' . $file)) ? $file : $default;
            }
            else
            {
                $file = $this->path->media . $this->model->getId() . '/' . $this->model->getAlias() . '.' . $this->model->getExtension();

                return ($file && file_exists($this->path->root . 'web' . $file)) ? $file : '#';
            }
        }

        return $default;
    }

    /**
     * @param string $filename
     *
     * @return Response
     */
    public function download($filename = '')
    {
        $response = new Response();
        if($this->isModelSet())
        {
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', $this->model->getMimeType());
            $response->headers->set(
                'Content-Disposition',
                'attachment; filename="' . ($filename ? $filename : $this->model->getName()) . '"'
            );
            $response->setContent(file_get_contents($this->path->fullOrigin . $this->model->getId()));
        }
        else
        {
            $response->setStatusCode(404);
        }

        return $response;
    }

    /**
     * Generate Image
     */
    public function generateImage()
    {
        $this->clearFolder($this->path->fullWeb);
        $this->generate();

        return $this;
    }

    /**
     * Generate Tag
     */
    public function generateTag()
    {
        $this->tag();

        return $this;
    }

    /**
     * @param string $root
     *
     * @return string
     */
    public function tagRoot($root = "")
    {
        $tagClassName = $this->objectManager->getCurrentModelName('FhmMediaBundle:MediaTag');
        if($root === '&user')
        {
            $root      = $this->tokenStorage->getToken()->getUser()->getUsername();
            $tagParent = $this->objectManager->getCurrentRepository('FhmMediaBundle:MediaTag')->getByAlias('users');
            $tag       = $this->objectManager->getCurrentRepository('FhmMediaBundle:MediaTag')->getByAlias($root);
            if(!$tagParent)
            {
                $tagParent = new $tagClassName;
                $tagParent->setName('users');
                $tagParent->setAlias('users');
                $tagParent->setActive(true);
                $tagParent->setPrivate(true);
                $this->objectManager->getManager()->persist($tagParent);
            }
            if(!$tag)
            {
                $tag = new $tagClassName;
                $tag->setName($root);
                $tag->setAlias($root);
                $tag->setParent($tagParent);
                $tag->setActive(true);
                $tag->setPrivate(true);
                $this->objectManager->getManager()->persist($tag);
            }
            $this->objectManager->getManager()->flush();

            return $tag->getId();
        }
        if($root)
        {
            $tag = $this->objectManager->getCurrentRepository('FhmMediaBundle:MediaTag')->getById($root);
            $tag = ($tag) ? $tag : $this->objectManager->getCurrentRepository('FhmMediaBundle:MediaTag')->getByAlias(
                $root
            );
            $tag = ($tag) ? $tag : $this->objectManager->getCurrentRepository('FhmMediaBundle:MediaTag')->getByName(
                $root
            );
            if(!$tag)
            {
                $tag = new $tagClassName;
                $tag->setName($root);
                $tag->setAlias($root);
                $tag->setActive(true);
                $this->objectManager->getManager()->persist($tag);
            }
            $this->objectManager->getManager()->flush();

            return $tag->getId();
        }

        return $root;
    }

    /**
     * Remove
     */
    public function remove()
    {
        if($this->isModelSet())
        {
            $files = array_diff(scandir($this->path->fullWeb), array('.', '..'));
            foreach($files as $file)
            {
                unlink($this->path->fullWeb . $file);
            }
            rmdir($this->path->fullWeb);
            if($this->model->getType() == 'image')
            {
                unlink($this->path->fullOrigin . $this->model->getId());
            }
        }

        return;
    }

    /**
     * Upload
     */
    private function upload()
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
        if($this->isModelSet() && $this->model->getType() == 'image')
        {
            $this->uploadImage();
            $this->generate();
        } // Other
        else
        {
            $this->uploadFile();
        }
    }

    /**
     * Upload image
     */
    private function uploadImage()
    {
        if($this->file instanceof UploadedFile)
        {
            $this->file->move($this->path->fullOrigin, $this->model->getId());
        }
    }

    /**
     * Upload file
     */
    private function uploadFile()
    {
        if($this->isModelSet() && $this->file && $this->model->getType() != 'image')
        {
            $this->file->move($this->path->fullWeb, $this->model->getAlias() . '.' . $this->model->getExtension());
        }
    }

    /**
     * Generate image
     */
    private function generate()
    {
        if(!$this->isModelSet())
        {
            return $this;
        }
        if($this->model->getType() == 'image')
        {
            // Copy image
            $source1          = $this->path->fullWeb . 'tmp1.' . $this->model->getExtension();
            $source2          = $this->path->fullWeb . 'tmp2.' . $this->model->getExtension();
            $originalFileName = $this->path->fullWeb . $this->model->getAlias() . '.' . $this->model->getExtension();
            copy($this->path->fullOrigin . $this->model->getId(), $originalFileName);
            copy($this->path->fullOrigin . $this->model->getId(), $source1);
            copy($this->path->fullOrigin . $this->model->getId(), $source2);
            // Initialization
            $sizeSource    = getimagesize($source1);
            $sizeWatermark = getimagesize($this->path->fullWatermark);
            $function1     = $this->getImagecreatefrom($this->model->getExtension());
            $function2     = $this->getImage($this->model->getExtension());
            // Resize watermarker
            $watermarkPercent = $sizeWatermark[0] > $sizeWatermark[1]
                ? $sizeWatermark[0] * 100 / ($sizeSource[0] - 40)
                : $sizeWatermark[1] * 100 / ($sizeSource[1] - 40);
            $watermarkWidth   = 100 * $sizeWatermark[0] / $watermarkPercent;
            $watermarkHeight  = 100 * $sizeWatermark[1] / $watermarkPercent;
            $watermarkX       = ($sizeSource[0] - $watermarkWidth) / 2;
            $watermarkY       = ($sizeSource[1] - $watermarkHeight) / 2;
            $watermark        = imagecreatetruecolor($watermarkWidth, $watermarkHeight);
            imagealphablending($watermark, false);
            imagesavealpha($watermark, true);
            $transparent = imagecolorallocatealpha($watermark, 255, 255, 255, 127);
            imagefilledrectangle($watermark, 0, 0, $sizeWatermark[0], $sizeWatermark[1], $transparent);
            imagecopyresampled(
                $watermark,
                imagecreatefrompng($this->path->fullWatermark),
                0,
                0,
                0,
                0,
                $watermarkWidth,
                $watermarkHeight,
                $sizeWatermark[0],
                $sizeWatermark[1]
            );
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
                copy(
                    $file['watermark'] ? $source2 : $source1,
                    $this->path->fullWeb . $file['name'] . '.' . $this->model->getExtension()
                );
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
                        $percent = ($sizeSource[0] / $file['width'] > $sizeSource[1] / $file['height'])
                            ? $file['width'] * 100 / $sizeSource[0]
                            : $file['height'] * 100 / $sizeSource[1];
                    }
                    else
                    {
                        $percent = $file['width'] >= $file['height']
                            ? $file['width'] * 100 / $sizeSource[0]
                            : $file['height'] * 100 / $sizeSource[1];
                    }
                    $width   = $percent * $sizeSource[0] / 100;
                    $height  = $percent * $sizeSource[1] / 100;
                    $offsetX = $file['width'] > $width ? ($file['width'] - $width) / 2 : 0;
                    $offsetY = $file['height'] > $height ? ($file['height'] - $height) / 2 : 0;
                    $object  = imagecreatetruecolor(
                        $file['width'] == 0 ? $width : $file['width'],
                        $file['height'] == 0 ? $height : $file['height']
                    );
                    imagealphablending($object, false);
                    imagesavealpha($object, true);
                    $transparent = imagecolorallocatealpha($object, 255, 255, 255, 127);
                    imagefilledrectangle(
                        $object,
                        0,
                        0,
                        $file['width'] == 0 ? $width : $file['width'],
                        $file['height'] == 0 ? $height : $file['height'],
                        $transparent
                    );
                    imagecopyresampled(
                        $object,
                        call_user_func(
                            $function1,
                            $this->path->fullWeb . $file['name'] . '.' . $this->model->getExtension()
                        ),
                        $offsetX,
                        $offsetY,
                        0,
                        0,
                        $width,
                        $height,
                        $sizeSource[0],
                        $sizeSource[1]
                    );
                    call_user_func(
                        $function2,
                        $object,
                        $this->path->fullWeb . $file['name'] . '.' . $this->model->getExtension()
                    );
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
    private function tag()
    {
        $tagClassName = $this->objectManager->getCurrentModelName('FhmMediaBundle:MediaTag');
        $tagType      = $this->objectManager->getCurrentRepository('FhmMediaBundle:MediaTag')->getByAlias(
            $this->model->getType()
        );
        $tagExtension = $this->objectManager->getCurrentRepository('FhmMediaBundle:MediaTag')->getByAlias(
            $this->model->getExtension()
        );
        if(!$tagType)
        {
            $tagType = new $tagClassName;
            $tagType->setName($this->model->getType());
            $tagType->setAlias($this->model->getType());
            $tagType->setActive(true);
            $this->objectManager->getManager()->persist($tagType);
        }
        if(!$tagExtension)
        {
            $tagExtension = new $tagClassName;
            $tagExtension->setName($this->model->getExtension());
            $tagExtension->setAlias($this->model->getExtension());
            $tagExtension->setParent($tagType);
            $tagExtension->setActive(true);
            $this->objectManager->getManager()->persist($tagExtension);
        }
        $this->model->addTag($tagType);
        $this->model->addTag($tagExtension);
        $this->objectManager->getManager()->persist($this->model);
        $this->objectManager->getManager()->flush();

        return $this;
    }

    /**
     * @param $folder
     *
     * @return $this
     */
    private function clearFolder($folder)
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
    private function filesInit($files)
    {
        $list = array();
        foreach($files as $name => $size)
        {
            $widthHeight = explode(':', $size);
            $list[$name] = array(
                'name'      => $name,
                'watermark' => false,
                'width'     => $widthHeight[0],
                'height'    => isset($widthHeight[1]) ? $widthHeight[1] : null,
            );
        }

        return $list;
    }

    /**
     * @param $extension
     *
     * @return string
     */
    private function getImagecreatefrom($extension)
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
    private function getImage($extension)
    {
        if($extension == 'jpg')
        {
            $extension = 'jpeg';
        }

        return 'image' . $extension;
    }
}