<?php
namespace Project\DefaultBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class TwigGlobal
 *
 * @package Project\DefaultBundle\Services
 */
class TwigGlobal extends FhmController
{
    private $session;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->session   = $this->container->get('session');
    }

    /**
     * @return $this
     */
    public function load()
    {
        return $this;
    }
}