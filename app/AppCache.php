<?php

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class AppCache extends HttpCache
{

    protected function getOptions()
    {
        return array(
            'debug'                  => false,
            'default_ttl'            => 3600,
            'private_headers'        => array('Authorization', 'Cookie'),
            'allow_reload'           => false,
            'allow_revalidate'       => false,
            'stale_while_revalidate' => 2,
            'stale_if_error'         => 60,
        );
    }

    protected function invalidate(Request $request, $catch=false)
    {
        if('PURGE'!=$request->getMethod())
        {
            return parent::invalidate($request,$catch);
        }

        if('127.0.0.1'!=$request->getClientIp())
        {
            return new Response('Methode de requete invalide',Response::HTTP_ALREADY_REPORTED);
        }

        $response= new Response();
        if($this->getStore()->purge($request->getUri()))
        {
            $response->setStatusCode(200,'Cache vidé');
        }else{
            $response->setStatusCode(404,'Page non trouvée');
        }

        return $response;

    }

}
