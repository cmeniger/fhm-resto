<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminControllerTest extends WebTestCase
{
    private $client = null;
    private $container;

    public function setUp()
    {
        $request = Request::createFromGlobals();
        self::bootKernel();

        $this->container = self::$kernel->getContainer();

        $this->client = static::createClient([],[
                'HTTP_HOST'     => $request->server->get('HTTP_HOST'),
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'admin',
            ]
        );
    }

    public function testSecuredHello()
    {
        $this->logIn();
        $this->client->request('GET', '/admin');
        $this->client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/admin/article/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context (defaults to the firewall name)
        $firewall = 'main';
        $token = new UsernamePasswordToken('admin', 'admin', $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        return;
    }

//    public function testIndex()
//    {
//        $client  = static::createClient();
//        $crawler = $client->request('GET', '/hello/Fabien');
//$this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
//}
}
