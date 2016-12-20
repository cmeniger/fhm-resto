<?php
namespace Fhm\MediaBundle\Twig;

use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MediaExtension
 *
 * @package Fhm\MediaBundle\Twig
 */
class MediaExtension extends \Twig_Extension
{
    protected $template;
    protected $service;
    protected $path;

    /**
     * MediaExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->template  = new \Twig_Environment(new \Twig_Loader_Filesystem($container->get('kernel')->getRootDir()));

        $this->service   = $container->get($container->getParameter('fhm_media')['service']);
        $this->path      = $this->service->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('blocAdmin', array($this, 'getBlocAdmin')),
            new \Twig_SimpleFilter('blocFront', array($this, 'getBlocFront')),
            new \Twig_SimpleFilter('blocSelector', array($this, 'getBlocSelector')),
            new \Twig_SimpleFilter('blocEditor', array($this, 'getBlocEditor')),
            new \Twig_SimpleFilter('download', array($this, 'getBlocDownload')),
            new \Twig_SimpleFilter('secure', array($this, 'getBlocSecure')),
            new \Twig_SimpleFilter('image', array($this, 'getImage')),
            new \Twig_SimpleFilter('video', array($this, 'getVideo')),
            new \Twig_SimpleFilter('media', array($this, 'getMedia')),
            new \Twig_SimpleFilter('small', array($this, 'getSmall')),
            new \Twig_SimpleFilter('medium', array($this, 'getMedium')),
            new \Twig_SimpleFilter('link', array($this, 'getLink')),
            new \Twig_SimpleFilter('dimension', array($this, 'getDimension'))
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'media_extension';
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     * @param object                          $instance
     *
     * @return string
     */
    public function getBlocAdmin($media, $instance)
    {
        return $this->template->render
        (
            '::FhmMedia/Template/Bloc/admin.' . ($media->getType() == 'image' ? 'image' : 'file') . '.html.twig',
            array
            (
                'document' => $media,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     * @param object                          $instance
     *
     * @return mixed
     */
    public function getBlocFront($media, $instance)
    {
        return $this->template->render
        (
            '::FhmMedia/Template/Bloc/front.' . ($media->getType() == 'image' ? 'image' : 'file') . '.html.twig',
            array
            (
                'document' => $media,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     * @param object                          $instance
     * @param string                          $selected
     *
     * @return mixed
     */
    public function getBlocSelector($media, $instance, $selected = '')
    {
        return $this->template->render
        (
            '::FhmMedia/Template/Bloc/selector.' . ($media->getType() == 'image' ? 'image' : 'file') . '.html.twig',
            array
            (
                'selected' => $selected,
                'document' => $media,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     * @param object                          $instance
     *
     * @return mixed
     */
    public function getBlocEditor($media, $instance)
    {
        return $this->template->render
        (
            '::FhmMedia/Template/Bloc/editor.' . ($media->getType() == 'image' ? 'image' : 'file') . '.html.twig',
            array
            (
                'document' => $media,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     * @param object                          $instance
     *
     * @return string
     */
    public function getBlocDownload($media, $instance, $format = 'origin')
    {
        return $this->template->render
        (
            '::FhmMedia/Template/Bloc/download.html.twig',
            array
            (
                'format'   => $format,
                'document' => $media,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     * @param object                          $instance
     *
     * @return string
     */
    public function getBlocSecure($media, $instance, $url, $title = '')
    {
        return $this->template->render
        (
            '::FhmMedia/Template/Bloc/secure.' . ($media->getType() == 'image' ? 'image' : 'file') . '.html.twig',
            array
            (
                'url'      => $url,
                'title'    => $title ? $title : $media->getName(),
                'document' => $media,
                'instance' => $instance
            )
        );
    }

    /**
     * @param        $media
     * @param string $format
     * @param null   $height
     * @param string $default
     *
     * @return string
     */
    public function getImage($media, $format = 'origin', $height = null, $default = 'default')
    {
        return "<img src='" . $this->getMedia($media, $format, $default) . "' " . ($height ? "style='height:" . $height . "px'" : "") . " alt='" . ($media ? $media->getName() : "") . "'>";
    }

    /**
     * @param     $url
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    public function getVideo($url, $width = 560, $height = 315)
    {
        return "<iframe width='" . $width . "' height='" . $height . "' src='" . $url . "' frameborder='0' allowfullscreen></iframe>";
    }

    /**
     * @param        $media
     * @param        $format
     * @param string $default
     *
     * @return string
     */
    public function getMedia($media, $format = 'origin', $default = 'default')
    {
        if($media)
        {
            return $this->service->setDocument($media)->getPathFile($format, $default);
        }
        elseif($default == "default")
        {
            $default = file_exists('../web/images/default.jpg') ? '/images/default.jpg' : $default;
            $default = file_exists('../web/images/default.png') ? '/images/default.png' : $default;
        }

        return $default;
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     *
     * @return string
     */
    public function getLink($media)
    {
        return $this->getMedia($media, 'origin');
    }

    /**
     * @param \Fhm\MediaBundle\Document\Media $media
     *
     * @return string
     */
    public function getDimension($media, $axe = '', $ratio = '')
    {
        $path = $this->service->setDocument($media)->getPath();
        if(!file_exists($path->fullWeb . 'origin.' . $media->getExtension()))
        {
            return '';
        }
        $size   = getimagesize($path->fullWeb . 'origin.' . $media->getExtension());
        $width  = $size[0];
        $height = $size[1];
        if($ratio > 0)
        {
            $width  = $width > $height ? $ratio : $ratio * $width / $height;
            $height = $height > $width ? $ratio : $ratio * $height / $width;
        }
        if($axe)
        {
            $axe = strtolower(trim($axe));
            $axe = ($axe != 'width' && $axe != 'height') ? 'width' : $axe;
        }

        return $axe ? $$axe : $width . ' x ' . $height;
    }
}