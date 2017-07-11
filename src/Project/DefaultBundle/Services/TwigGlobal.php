<?php

namespace Project\DefaultBundle\Services;

use Fhm\FhmBundle\Services\Tools;

/**
 * Class TwigGlobal
 *
 * @package Project\DefaultBundle\Services
 */
class TwigGlobal
{
    private $session;
    private $tools;
    private $twig;

    /**
     * TwigGlobal constructor.
     *
     * @param Tools                                             $tools
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Twig_Environment                                 $twig
     */
    public function __construct(Tools $tools, \Symfony\Component\HttpFoundation\Session\Session $session, \Twig_Environment $twig)
    {
        $this->tools   = $tools;
        $this->session = $session;
        $this->twig    = $twig;
    }

    /**
     * @return $this
     */
    public function load()
    {
        $this->twig->addGlobal('site', $this->tools->dmRepository('FhmFhmBundle:Site')->getDefault());

        return $this;
    }
}