<?php
namespace Fhm\MediaBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaTagExtension extends \Twig_Extension
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tagBreadcrumbs', array($this, 'getBreadcrumbs')),
            new \Twig_SimpleFilter('tagRoute', array($this, 'getRoute')),
            new \Twig_SimpleFilter('tagLabel', array($this, 'getTag')),
            new \Twig_SimpleFilter('tagBlocAdmin', array($this, 'getBlocAdmin')),
            new \Twig_SimpleFilter('tagBlocFront', array($this, 'getBlocFront')),
            new \Twig_SimpleFilter('tagBlocSelector', array($this, 'getBlocSelector')),
            new \Twig_SimpleFilter('tagBlocEditor', array($this, 'getBlocEditor'))
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'media_tag_extension';
    }

    /**
     * @param        $tag
     * @param string $root
     *
     * @return string
     */
    public function getBreadcrumbs($tag, $root = "")
    {
        $current = $tag->getId();
        $html    = "";
        while($tag)
        {
            if($current == $tag->getId())
            {
                $html = "<li class='current'><a href='#' media-tag='" . $tag->getId() . "'>" . $tag->getName() . "</a></li>" . $html;
            }
            else
            {
                $html = "<li class=''><a href='#' media-tag='" . $tag->getId() . "'>" . $tag->getName() . "</a></li>" . $html;
            }
            $tag = $tag->getParent() && $tag->getParent()->getId() != $root ? $tag->getParent() : false;
        }

        return "<ul class='tag breadcrumbs'>" . $html . "</ul>";
    }

    /**
     * @param \Fhm\MediaBundle\Document\MediaTag $tag
     *
     * @return string
     */
    public function getRoute($tag)
    {
        $tabs = $tag->getRouteObject();
        $html = "<ul class='tag'>";
        foreach($tabs as $tab)
        {
            $current = end($tabs) === $tab;
            $color   = $tab->getColor() ? "style='background-color:" . $tab->getColor() . ";'" : "";
            $html .= "<li><span class='label round " . ($current ? 'current' : 'parent') . "' " . $color . ">" . $tab->getName() . ($tab->getPrivate() ? " <i class='fa fa-lock'></i> " : "") . "</span></li>";
        }
        $html .= "</ul>";

        return $html;
    }

    /**
     * @param \Fhm\MediaBundle\Document\MediaTag $tag
     *
     * @return string
     */
    public function getTag($tag)
    {
        $color = $tag->getColor() ? "style='background-color:" . $tag->getColor() . ";'" : "";

        return "<span class='label round tag' " . $color . ">" . $tag->getName() . ($tag->getPrivate() ? " <i class='fa fa-lock'></i>" : "") . "</span>";
    }

    /**
     * @param \Fhm\MediaBundle\Document\MediaTag $tag
     * @param object                             $instance
     *
     * @return string
     */
    public function getBlocAdmin($tag, $instance)
    {
        return $this->container->get('templating')->render
        (
            '::FhmMedia/Template/Bloc/admin.tag.html.twig',
            array
            (
                'document' => $tag,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\MediaTag $tag
     * @param object                             $instance
     *
     * @return string
     */
    public function getBlocFront($tag, $instance)
    {
        return $this->container->get('templating')->render
        (
            '::FhmMedia/Template/Bloc/front.tag.html.twig',
            array
            (
                'document' => $tag,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\MediaTag $tag
     * @param object                             $instance
     *
     * @return string
     */
    public function getBlocSelector($tag, $instance)
    {
        return $this->container->get('templating')->render
        (
            '::FhmMedia/Template/Bloc/selector.tag.html.twig',
            array
            (
                'document' => $tag,
                'instance' => $instance
            )
        );
    }

    /**
     * @param \Fhm\MediaBundle\Document\MediaTag $tag
     * @param object                             $instance
     *
     * @return string
     */
    public function getBlocEditor($tag, $instance)
    {
        return $this->container->get('templating')->render
        (
            '::FhmMedia/Template/Bloc/editor.tag.html.twig',
            array
            (
                'document' => $tag,
                'instance' => $instance
            )
        );
    }
}
