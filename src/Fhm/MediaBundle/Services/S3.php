<?php
namespace Fhm\MediaBundle\Services;

use Aws\Sdk;
use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class S3
 *
 * @package Fhm\MediaBundle\Services
 */
class S3
{
    protected $fhm_tools;
    protected $model;
    private $files;
    private $file;
    private $path;
    private $aws_client;
    private $aws_bucket;
    private $aws_environment;
    private $aws_host;

    /**
     * Local constructor.
     *
     * @param \Fhm\FhmBundle\Services\Tools $tools
     */
    public function __construct(Tools $tools)
    {
        $this->fhm_tools = $tools;
        $this->files = $this->_filesInit($this->fhm_tools->getParameters('files', 'fhm_media'));
        $this->file = null;
        // Init AWS
        $this->_awsInit();
        // Path
        $this->path = new \stdClass();
        $this->path->root = '/';
        $this->path->origin = 'media/';
        $this->path->media = 'datas/';
        $this->path->web = 'web/';
        $this->path->watermark = 'watermark.png';
        $this->path->files = '';
        $this->path->localRoot = $this->fhm_tools->getContainer()->get('kernel')->getRootDir().'/../';
        $this->path->local = $this->path->localRoot.$this->path->origin;
        $this->path->fullWeb = '';
        $this->path->fullOrigin = $this->path->root.$this->path->origin;
        $this->path->fullWatermark = $this->path->localRoot.'web/images/common/'.$this->path->watermark;
        $this->path->fullLocal = '';
        $this->path->fullLocalOrigin = '';
        $this->path->fullLocalWeb = '';
    }

    /**
     * @return mixed
     */
    public function getDocument()
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
        $this->model = $model;
        $this->file = $model->getFile();
        $this->path->files = $model->getId().'/';
        $this->path->fullWeb = $this->path->root.$this->path->media.$this->path->files;
        $this->path->fullLocal = $this->path->local.$this->path->files;
        $this->path->fullLocalOrigin = $this->path->localRoot.$this->path->origin;
        $this->path->fullLocalWeb = $this->path->localRoot.$this->path->web.$this->path->media.$this->path->origin.$this->path->files;

        return $this;
    }

    /**
     * @param $datas
     *
     * @return $this
     */
    public function setWatermark($datas)
    {
        if ($datas) {
            foreach ($datas as $name => $value) {
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
        if ($default === "default") {
            $default = file_exists('../web/images/default.jpg') ? '/images/default.jpg' : $default;
            $default = file_exists('../web/images/default.png') ? '/images/default.png' : $default;
        }
        if ($this->model->getType() == 'image') {
            $file = $this->path->fullWeb.$format.'.'.$this->model->getExtension();

            return ($file && $this->_awsExist($file)) ? $this->_awsPath($file) : $default;
        } else {
            $file = $this->path->fullWeb.$this->model->getAlias().'.'.$this->model->getExtension();

            return ($file && $this->_awsExist($file)) ? $this->_awsPath($file) : '#';
        }
    }

    /**
     * @param string $filename
     *
     * @return Response
     */
    public function download($filename = '')
    {
        $object = $this->aws_client->getObject(
            [
                'Bucket' => $this->aws_bucket,
                'Key' => $this->aws_environment.$this->path->fullOrigin.$this->model->getId(),
            ]
        );
        $response = new Response();
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', $this->model->getMimeType());
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="'.($filename ? $filename : $this->model->getName()).'"'
        );
        $response->setContent($object['body']);

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
        if ($root === '&user') {
            $root = $this->fhm_tools->getUser()->getUsername();
            $tagParent = $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getByAlias('users');
            $tag = $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getByAlias($root);
            if (!$tagParent) {
                $tagParent = new \Fhm\MediaBundle\Document\MediaTag();
                $tagParent->setName('users');
                $tagParent->setAlias('users');
                $tagParent->setActive(true);
                $tagParent->setPrivate(true);
                $this->fhm_tools->dmPersist($tagParent);
            }
            if (!$tag) {
                $tag = new \Fhm\MediaBundle\Document\MediaTag();
                $tag->setName($root);
                $tag->setAlias($root);
                $tag->setParent($tagParent);
                $tag->setActive(true);
                $tag->setPrivate(true);
                $this->fhm_tools->dmPersist($tag);
            }

            return $tag->getId();
        }
        if ($root) {
            $tag = $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getById($root);
            $tag = ($tag) ? $tag : $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getByAlias($root);
            $tag = ($tag) ? $tag : $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getByName($root);
            if (!$tag) {
                $tag = new \Fhm\MediaBundle\Document\MediaTag();
                $tag->setName($root);
                $tag->setAlias($root);
                $tag->setActive(true);
                $this->fhm_tools->dmPersist($tag);
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
        $this->_awsClearFolder($this->path->fullWeb);
        $this->_awsClearObject($this->aws_environment.$this->path->fullOrigin.$this->model->getId());

        return $this;
    }

    /**
     * @param            $src
     * @param            $dst
     * @param bool|false $private
     *
     * @return $this
     */
    public function copy($src, $dst, $private = false)
    {
        if (is_dir($src)) {
            $files = array_diff(scandir($src), array('.', '..'));
            foreach ($files as $file) {
                $this->aws_client->putObject(
                    array(
                        'Bucket' => $this->aws_bucket,
                        'Key' => $this->aws_environment.$dst.$file,
                        'SourceFile' => $src.$file,
                        'ACL' => $private ? 'private' : 'public-read',
                    )
                );
            }
        } else {
            $this->aws_client->putObject(
                array(
                    'Bucket' => $this->aws_bucket,
                    'Key' => $this->aws_environment.$dst,
                    'SourceFile' => $src,
                    'ACL' => $private ? 'private' : 'public-read',
                )
            );
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function copyLocal()
    {
        // Web
        $objects = $this->aws_client->listObjects(
            array('Bucket' => $this->aws_bucket, 'Prefix' => $this->aws_environment.$this->path->fullWeb)
        );
        if ($objects['Contents']) {
            if (!is_dir($this->path->fullLocalWeb)) {
                @mkdir($this->path->localRoot.$this->path->web.$this->path->media, 0777, true);
                @mkdir($this->path->localRoot.$this->path->web.$this->path->media.$this->path->origin, 0777, true);
                @mkdir($this->path->fullLocalWeb, 0777, true);
            }
            foreach ($objects['Contents'] as $object) {
                $data = explode('/', $object['Key']);
                $this->aws_client->getObject(
                    [
                        'Bucket' => $this->aws_bucket,
                        'Key' => $object['Key'],
                        'SaveAs' => $this->path->fullLocalWeb.array_pop($data),
                    ]
                );
            }
        }
        // Media
        if ($this->_awsExist($this->path->fullOrigin.$this->model->getId())) {
            if (!is_dir($this->path->fullLocalOrigin)) {
                @mkdir($this->path->fullLocalOrigin, 0777, true);
            }
            $this->aws_client->getObject(
                [
                    'Bucket' => $this->aws_bucket,
                    'Key' => $this->aws_environment.$this->path->fullOrigin.$this->model->getId(),
                    'SaveAs' => $this->path->fullLocalOrigin.$this->model->getId(),
                ]
            );
        }

        return $this;
    }

    /**
     * Upload
     */
    private function _upload()
    {
        if ($this->file) {
            if (!is_dir($this->path->fullLocal)) {
                mkdir($this->path->fullLocal, 0777, true);
            }
            if (!is_dir($this->path->fullLocal.$this->path->origin)) {
                mkdir($this->path->fullLocal.$this->path->origin, 0777, true);
            }
            if (!is_dir($this->path->fullLocal.$this->path->media)) {
                mkdir($this->path->fullLocal.$this->path->media, 0777, true);
            }
        }
        // Image
        if ($this->model->getType() == 'image') {
            $this->_uploadImage();
            $this->_generateImage();
        } // Other
        else {
            $this->_uploadFile();
            $this->_generateFile();
        }
    }

    /**
     * Upload image
     */
    private function _uploadImage()
    {
        if ($this->file && $this->model->getType() == 'image') {
            $this->file->move($this->path->fullLocal.$this->path->origin, $this->model->getId());
        }
    }

    /**
     * Upload file
     */
    private function _uploadFile()
    {
        if ($this->file && $this->model->getType() != 'image') {
            $this->file->move(
                $this->path->fullLocal.$this->path->media,
                $this->model->getAlias().'.'.$this->model->getExtension()
            );
        }
    }

    /**
     * Generate image
     */
    private function _generateImage()
    {
        if ($this->model->getType() == 'image') {
            // Copy image
            $source1 = $this->path->fullLocal.$this->path->media.'tmp1.'.$this->model->getExtension();
            $source2 = $this->path->fullLocal.$this->path->media.'tmp2.'.$this->model->getExtension();
            $originalFileName = $this->path->fullLocal.$this->path->media.$this->model->getAlias(
                ).'.'.$this->model->getExtension();
            copy($this->path->fullLocal.$this->path->origin.$this->model->getId(), $originalFileName);
            copy($this->path->fullLocal.$this->path->origin.$this->model->getId(), $source1);
            copy($this->path->fullLocal.$this->path->origin.$this->model->getId(), $source2);
            // Initialization
            $sizeSource = getimagesize($source1);
            $sizeWatermark = getimagesize($this->path->fullWatermark);
            $function1 = $this->_getImagecreatefrom($this->model->getExtension());
            $function2 = $this->_getImage($this->model->getExtension());
            // Resize watermarker
            $watermarkPercent = $sizeWatermark[0] > $sizeWatermark[1] ? $sizeWatermark[0] * 100 / ($sizeSource[0] - 40) : $sizeWatermark[1] * 100 / ($sizeSource[1] - 40);
            $watermarkWidth = 100 * $sizeWatermark[0] / $watermarkPercent;
            $watermarkHeight = 100 * $sizeWatermark[1] / $watermarkPercent;
            $watermarkX = ($sizeSource[0] - $watermarkWidth) / 2;
            $watermarkY = ($sizeSource[1] - $watermarkHeight) / 2;
            $watermark = imagecreatetruecolor($watermarkWidth, $watermarkHeight);
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
            imagepng($watermark, $this->path->fullLocal.$this->path->media.$this->path->watermark);
            // Add watermarker
            $object = call_user_func($function1, $source2);
            imagecopy($object, $watermark, $watermarkX, $watermarkY, 0, 0, $watermarkWidth, $watermarkHeight);
            call_user_func($function2, $object, $source2);
            imagedestroy($object);
            imagedestroy($watermark);
            // Generate images file
            foreach ($this->files as $file) {
                // Copy image
                copy(
                    $file['watermark'] ? $source2 : $source1,
                    $this->path->fullLocal.$this->path->media.$file['name'].'.'.$this->model->getExtension()
                );
                // Square
                if ($file['width'] > 0 && $file['height'] === null) {
                    $size = $file['width'];
                    $file['width'] = $sizeSource[0] >= $sizeSource[1] ? $size : 0;
                    $file['height'] = $sizeSource[0] < $sizeSource[1] ? $size : 0;
                }
                // Resize
                if ($file['width'] + $file['height'] > 0) {
                    if ($file['width'] > 0 && $file['height'] > 0) {
                        $percent = ($sizeSource[0] / $file['width'] > $sizeSource[1] / $file['height']) ? $file['width'] * 100 / $sizeSource[0] : $file['height'] * 100 / $sizeSource[1];
                    } else {
                        $percent = $file['width'] >= $file['height'] ? $file['width'] * 100 / $sizeSource[0] : $file['height'] * 100 / $sizeSource[1];
                    }
                    $width = $percent * $sizeSource[0] / 100;
                    $height = $percent * $sizeSource[1] / 100;
                    $offsetX = $file['width'] > $width ? ($file['width'] - $width) / 2 : 0;
                    $offsetY = $file['height'] > $height ? ($file['height'] - $height) / 2 : 0;
                    $object = imagecreatetruecolor(
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
                            $this->path->fullLocal.$this->path->media.$file['name'].'.'.$this->model->getExtension()
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
                        $this->path->fullLocal.$this->path->media.$file['name'].'.'.$this->model->getExtension()
                    );
                    imagedestroy($object);
                }
            }
            // Delete temporary image
            unlink($source1);
            unlink($source2);
            unlink($this->path->fullLocal.$this->path->media.$this->path->watermark);
            // Copy
            $this->copy($this->path->fullLocal.$this->path->origin, $this->path->fullOrigin, true);
            $this->copy($this->path->fullLocal.$this->path->media, $this->path->fullWeb);
            // Delete temporary folder
            $this->_deleteFolder($this->path->fullLocal);
        }

        return $this;
    }

    /**
     * Generate file
     */
    private function _generateFile()
    {
        if ($this->model->getType() != 'image') {
            // Copy
            $this->copy($this->path->fullLocal.$this->path->media, $this->path->fullWeb);
            // Delete temporary folder
            $this->_deleteFolder($this->path->fullLocal);
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function _tag()
    {
        $tagType = $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getByAlias($this->model->getType());
        $tagExtension = $this->fhm_tools->dmRepository('FhmMediaBundle:MediaTag')->getByAlias(
            $this->model->getExtension()
        );
        if (!$tagType) {
            $tagType = new \Fhm\MediaBundle\Document\MediaTag();
            $tagType->setName($this->model->getType());
            $tagType->setAlias($this->model->getType());
            $tagType->setActive(true);
            $this->fhm_tools->dmPersist($tagType);
        }
        if (!$tagExtension) {
            $tagExtension = new \Fhm\MediaBundle\Document\MediaTag();
            $tagExtension->setName($this->model->getExtension());
            $tagExtension->setAlias($this->model->getExtension());
            $tagExtension->setParent($tagType);
            $tagExtension->setActive(true);
            $this->fhm_tools->dmPersist($tagExtension);
        }
        $this->model->addTag($tagType);
        $this->model->addTag($tagExtension);
        $this->fhm_tools->dmPersist($this->model);

        return $this;
    }

    /**
     * @param $folder
     *
     * @return $this
     */
    private function _clearFolder($folder)
    {
        $this->_awsClearFolder($folder);
        if ($this->_awsExist($this->path->fullOrigin.$this->model->getId())) {
            if (!is_dir($this->path->fullLocal.$this->path->origin)) {
                @mkdir($this->path->fullLocal, 0777, true);
                @mkdir($this->path->fullLocal.$this->path->origin, 0777, true);
                @mkdir($this->path->fullLocal.$this->path->media, 0777, true);
            }
            $this->aws_client->getObject(
                [
                    'Bucket' => $this->aws_bucket,
                    'Key' => $this->aws_environment.$this->path->fullOrigin.$this->model->getId(),
                    'SaveAs' => $this->path->fullLocal.$this->path->origin.$this->model->getId(),
                ]
            );
        }

        return $this;
    }

    /**
     * @param $folder
     *
     * @return $this
     */
    private function _deleteFolder($folder)
    {
        $folder = substr($folder, -1, 1) != '/' ? $folder.'/' : $folder;
        $files = array_diff(scandir($folder), array('.', '..'));
        foreach ($files as $file) {
            is_dir($folder.$file) ? $this->_deleteFolder($folder.$file) : unlink($folder.$file);
        }
        // TODO supprimer un fichier le remplace par un fichier .nfsxxxx du coup le rmdir ne fonctionne pas mais il n'y a pas de fichier dans le dossier
        rmdir($folder);

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
        foreach ($files as $name => $size) {
            $size = explode(':', $size);
            $list[$name] = array(
                'name' => $name,
                'watermark' => false,
                'width' => $size[0],
                'height' => isset($size[1]) ? $size[1] : null,
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
        if ($extension == 'jpg') {
            $extension = 'jpeg';
        }

        return 'imagecreatefrom'.$extension;
    }

    /**
     * @param $extension
     *
     * @return string
     */
    private function _getImage($extension)
    {
        if ($extension == 'jpg') {
            $extension = 'jpeg';
        }

        return 'image'.$extension;
    }

    /**
     * @return $this
     */
    private function _awsInit()
    {
        $parameters = $this->fhm_tools->getParameters(array(), 'aws');
        // SDK
        $sdk = new Sdk(
            [
                'version' => $parameters['sdk']['version'],
                'region' => $parameters['sdk']['region'],
                'credentials' => [
                    'key' => $parameters['sdk']['key'],
                    'secret' => $parameters['sdk']['secret'],
                ],
            ]
        );
        // Client
        $client = $sdk->createS3();
        // Bucket
        if (!$client->doesBucketExist($parameters['s3']['bucket'])) {
            $client->createBucket(array('Bucket' => $parameters['s3']['bucket']));
        }
        // Properties
        $this->aws_client = $client;
        $this->aws_bucket = $parameters['s3']['bucket'];
        $this->aws_environment = $parameters['s3']['environment'];
        $this->aws_host = $parameters['s3']['host'];

        return $this;
    }

    /**
     * @return $this
     */
    private function _awsExist($file)
    {
        return $this->aws_client->doesObjectExist($this->aws_bucket, $this->aws_environment.$file);
    }

    /**
     * @param $file
     * @return string
     */
    private function _awsPath($file)
    {
        return $this->aws_host.$this->aws_bucket.'/'.$this->aws_environment.$file;
    }

    /**
     * @param $folder
     *
     * @return $this
     */
    private function _awsClearFolder($folder)
    {
        $objects = $this->aws_client->listObjects(
            array('Bucket' => $this->aws_bucket, 'Prefix' => $this->aws_environment.$folder)
        );
        if ($objects['Contents']) {
            foreach ($objects['Contents'] as $object) {
                $this->_awsClearObject($object['Key']);
            }
        }

        return $this;
    }

    /**
     * @param $key
     *
     * @return $this
     */
    private function _awsClearObject($key)
    {
        $this->aws_client->deleteObject(
            array(
                'Bucket' => $this->aws_bucket,
                'Key' => $key,
            )
        );

        return $this;
    }
}