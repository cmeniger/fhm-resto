<?php
namespace Project\DefaultBundle\Services;

use Fhm\FhmBundle\Services\Tools;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Cache
 *
 * @package Project\DefaultBundle\Services
 */
class Cache
{
    private $session;
    private $tools;

    /**
     * Cache constructor.
     * @param $tools
     */
    public function __construct(Tools $tools, $session)
    {
        $this->tools     = $tools;
        $this->session   = $session;
    }

    /**
     * @param int $maxage
     * @param int $expires
     * @param bool $nocache
     * @return Response
     */
    public function getResponseCache($maxage = 0, $expires = 0, $nocache = false)
    {
        // HTTP Cache
        $response = new Response();
        // Don't cache anything
        if ($nocache) {
            $response->setPrivate();
            $response->setMaxAge(0);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('max-age', 0);
        } else {
            if (empty($maxage)) {
                $maxage = $this->tools->getParameters('maxage', 'fhm_cache');
            }
            if (empty($expires)) {
                $expires = $this->tools->getParameters('expires', 'fhm_cache');
            }
            if (is_int($expires)) {
                $expires = sprintf('+%s seconds', $expires);
            }
            $response->setMaxAge($maxage);
            $response->setSharedMaxAge($maxage);
            $dateExpires = new \DateTime();
            $dateExpires->modify($expires);
            $response->setExpires($dateExpires);
            $response->setPublic();
            // Check that the Response is not modified for the given Request
            if ($response->isNotModified($this->getRequest())) {
                return $response;
            }
        }

        return $response;
    }
}