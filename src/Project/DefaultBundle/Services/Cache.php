<?php
namespace Project\DefaultBundle\Services;

use Fhm\FhmBundle\Controller\FhmController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Cache
 *
 * @package Project\DefaultBundle\Services
 */
class Cache extends FhmController
{
    private $session;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->session   = $this->container->get('request')->getSession();
    }

    /**
     * @return $this
     */
    public function getResponseCache($maxage = 0, $expires = 0, $nocache = false)
    {
        // HTTP Cache
        $response = new Response();
        // Don't cache anything
        if($nocache)
        {
            $response->setPrivate();
            $response->setMaxAge(0);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('max-age', 0);
        }
        else
        {
            if(empty($maxage))
            {
                $maxage = $this->getParameters('maxage', 'fhm_cache');
            }
            if(empty($expires))
            {
                $expires = $this->getParameters('expires', 'fhm_cache');
            }
            if(is_int($expires))
            {
                $expires = sprintf('+%s seconds', $expires);
            }
            $response->setMaxAge($maxage);
            $response->setSharedMaxAge($maxage);
            $dateExpires = new \DateTime();
            $dateExpires->modify($expires);
            $response->setExpires($dateExpires);
            $response->setPublic();
            // Check that the Response is not modified for the given Request
            if($response->isNotModified($this->getRequest()))
            {
                return $response;
            }
        }

        return $response;
    }
}