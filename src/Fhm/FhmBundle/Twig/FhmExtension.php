<?php
namespace Fhm\FhmBundle\Twig;

use Fhm\FhmBundle\Services\Schedules;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Intl\Intl;

/**
 * Class FhmExtension
 * @package Fhm\FhmBundle\Twig
 */
class FhmExtension extends \Twig_Extension
{
    protected $session;
    protected $translator;

    /**
     * FhmExtension constructor.
     * @param Session $session
     * @param Translator $translator
     * @param Schedules $schedules
     */
    public function __construct(Session $session, Translator $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
//        $this->fhmSchedule= $schedules;
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
            new \Twig_SimpleFilter(
                'schedulesStateHtml',
                array($this, 'getSchedulesStateHtml'),
                array('needs_environment' => true)
            ),
        );
    }

    /**
     * @param $code
     * @param null $height
     * @return string
     */
    public function getFlag($code, $height = null)
    {
        $code = strtolower($code);
        $trans = $this->getCountry($code);
        $html = "<img 
                    src='".$this->getFlagUrl($code)."'
                    alt='".$trans."' 
                    title='".$trans."' 
                    class='flag' 
                    style='".($height ? "height:".$height."px" : "")."'
                 />";

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

        return $code ? Intl::getRegionBundle()->getCountryName($code) : $this->translator->trans(
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
        return $data ? $this->fhmSchedule->setData($data)->getValue(
            $key
        ) : "<span class='schedules nodata'>".$this->translator->trans(
            'fhm.schedules.nodata',
            array(),
            'FhmFhmBundle'
        )
            ."</span>";
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
        return $this->fhmSchedule->setData($data)->getState();
    }

    /**
     * @param \Twig_Environment $env
     * @param $data
     * @param bool $class
     * @param bool $text
     * @param bool $indicator
     * @return mixed|string
     */
    public function getSchedulesStateHtml(
        \Twig_Environment $env,
        $data,
        $class = true,
        $text = true,
        $indicator = false
    ) {
        return $env->render(
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
        return $this->session->get('_locale');
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
}
