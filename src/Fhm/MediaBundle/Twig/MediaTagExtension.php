<?php
namespace Fhm\MediaBundle\Twig;

/**
 * Class MediaTagExtension
 *
 * @package Fhm\MediaBundle\Twig
 */
class MediaTagExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'tagBreadcrumbs',
                array($this, 'getBreadcrumbs')
            ),
            new \Twig_SimpleFilter(
                'tagRoute',
                array($this, 'getRoute')
            ),
            new \Twig_SimpleFilter(
                'tagLabel',
                array($this, 'getTag')
            ),
            new \Twig_SimpleFilter(
                'tagBlocAdmin',
                array($this, 'getBlocAdmin'),
                array('needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'tagBlocFront',
                array($this, 'getBlocFront'),
                array('needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'tagBlocSelector',
                array($this, 'getBlocSelector'),
                array('needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'tagBlocEditor',
                array($this, 'getBlocEditor'),
                array('needs_environment' => true)
            ),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'media_tag_extension';
    }

    /**
     * @param $tag
     * @param string $root
     * @return string
     */
    public function getBreadcrumbs($tag, $root = "")
    {
        $current = $tag->getId();
        $html = "";
        while ($tag) {
            if ($current == $tag->getId()) {
                $html = "<li class='current'><a href='#' media-tag='".$tag->getId()."'>".$tag->getName(
                    )."</a></li>".$html;
            } else {
                $html = "<li class=''><a href='#' media-tag='".$tag->getId()."'>".$tag->getName()."</a></li>".$html;
            }
            $tag = $tag->getParent() && $tag->getParent()->getId() != $root ? $tag->getParent() : false;
        }

        return "<ul class='tag breadcrumbs'>".$html."</ul>";
    }

    /**
     * @param $tag
     * @return string
     */
    public function getRoute($tag)
    {
        $tabs = $tag->getRouteObject();
        $html = "<ul class='tag'>";
        foreach ($tabs as $tab) {
            $current = end($tabs) === $tab;
            $color = $tab->getColor() ? "style='background-color:".$tab->getColor().";'" : "";
            $html .= "<li><span class='label round ".($current ? 'current' : 'parent')."' ".$color.">".$tab->getName(
                ).($tab->getPrivate() ? " <i class='fa fa-lock'></i> " : "")."</span></li>";
        }
        $html .= "</ul>";

        return $html;
    }

    /**
     * @param $tag
     * @return string
     */
    public function getTag($tag)
    {
        $color = $tag->getColor() ? "style='background-color:".$tag->getColor().";'" : "";

        return "<span class='label round tag' ".$color.">".$tag->getName().($tag->getPrivate(
        ) ? " <i class='fa fa-lock'></i>" : "")."</span>";
    }

    /**
     * @param \Twig_Environment $env
     * @param $tag
     * @return mixed|string
     */
    public function getBlocAdmin(\Twig_Environment $env, $tag)
    {
        return $env->render(
            '::FhmMedia/Template/Bloc/admin.tag.html.twig',
            array(
                'document' => $tag
            )
        );
    }

    /**
     * @param \Twig_Environment $env
     * @param $tag
     * @return mixed|string
     */
    public function getBlocFront(\Twig_Environment $env, $tag)
    {
        return $env->render(
            '::FhmMedia/Template/Bloc/front.tag.html.twig',
            array(
                'document' => $tag
            )
        );
    }

    /**
     * @param \Twig_Environment $env
     * @param $tag
     * @return mixed|string
     */
    public function getBlocSelector(\Twig_Environment $env, $tag)
    {
        return $env->render(
            '::FhmMedia/Template/Bloc/selector.tag.html.twig',
            array(
                'document' => $tag
            )
        );
    }

    /**
     * @param \Twig_Environment $env
     * @param $tag
     * @return mixed|string
     */
    public function getBlocEditor(\Twig_Environment $env, $tag)
    {
        return $env->render(
            '::FhmMedia/Template/Bloc/editor.tag.html.twig',
            array(
                'document' => $tag
            )
        );
    }
}
