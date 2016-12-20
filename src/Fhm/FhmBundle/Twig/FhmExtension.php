<?php
namespace Fhm\FhmBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Intl\Intl;

/**
 * Class FhmExtension
 * @package Fhm\FhmBundle\Twig
 */
class FhmExtension extends \Twig_Extension
{
    protected $container;

    protected $instance;

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
            new \Twig_SimpleFilter('flag', array($this, 'getFlag')),
            new \Twig_SimpleFilter('flagUrl', array($this, 'getFlagUrl')),
            new \Twig_SimpleFilter('country', array($this, 'getCountry')),
            new \Twig_SimpleFilter('schedules', array($this, 'getSchedules')),
            new \Twig_SimpleFilter('schedulesClose', array($this, 'getSchedulesClose')),
            new \Twig_SimpleFilter('schedulesState', array($this, 'getSchedulesState')),
            new \Twig_SimpleFilter('schedulesStateHtml', array($this, 'getSchedulesStateHtml')),
        );
    }

    /**
     * @param $code
     *
     * @return string
     */
    public function getFlag($code, $height = null)
    {
        $code = strtolower($code);
        $trans = $this->getCountry($code);
        $html = "<img src='".$this->getFlagUrl(
                $code
            )."' alt='".$trans."' title='".$trans."' class='flag' style='".($height ? "height:".$height."px" : "")."'/>";

        return $html;
    }

    /**
     * @param $code
     *
     * @return string
     */
    public function getFlagUrl($code)
    {
        $code = $code ? strtolower($code) : 'all';
        $file = file_exists(
            '../web/images/flags/'.$code.'.png'
        ) ? '/images/flags/'.$code.'.png' : '/images/flags/default.png';

        return $file;
    }

    /**
     * @param $code
     *
     * @return string
     */
    public function getCountry($code)
    {
        \Locale::setDefault($this->getLocale());
        $code = strtoupper($code);
        $code = $code == 'EN' ? 'GB' : $code;

        return $code ? Intl::getRegionBundle()->getCountryName($code) : $this->container->get('translator')->trans(
            'fhm.language.code.all',
            array(),
            'FhmFhmBundle'
        );
    }

    /**
     * @param        $data
     * @param string $key
     *
     * @return mixed
     */
    public function getSchedules($data, $key = '')
    {
        return $data ? $this->container->get('fhm_schedules')->setData($data)->getValue(
            $key
        ) : "<span class='schedules nodata'>".$this->container->get('translator')->trans(
                'fhm.schedules.nodata',
                array(),
                'FhmFhmBundle'
            )."</span>";
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function getSchedulesClose($data)
    {
        return $this->getSchedules($data, 'close_enable');
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function getSchedulesState($data)
    {
        return $this->container->get('fhm_schedules')->setData($data)->getState();
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function getSchedulesStateHtml($data, $class = true, $text = true, $indicator = false)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->container->getParameter('kernel.root_dir')));

        return $twig->render(
            '::FhmFhm/Template/schedules.state.html.twig',
            array(
                'show_class' => $class,
                'show_text' => $text,
                'show_indicator' => $indicator,
                'state' => $this->getSchedulesState($data),
            )
        );
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->container->get('session')->get('_locale');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'fhm_extension';
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }

}
