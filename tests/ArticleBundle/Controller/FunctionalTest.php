<?php

namespace Tests\Controller;

use Doctrine\Tests\Common\DataFixtures\TestDocument\Role;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Tests\Authentication\Token\UsernamePasswordTokenTest;

/*
Functional tests check the integration of the different layers of an application (from the routing to the views). They are no different from unit tests as far as PHPUnit is concerned, but they have a very specific workflow:
- Make a request
- Test the response
- Click on a link or submit a form
- Test the response
- Rinse and repeat
*/
class FunctionalTest extends WebTestCase
{
    private $client = null;
    private $container;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();

        $this->client = static::createClient();
    }

    public function testShow()
    {
        $this->logIn();
        $this->client->request('GET', '/admin');
        $this->client->followRedirect();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

    }

    public function testCreate()
    {
        $this->client->request('GET', '/admin/article/create');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdate()
    {
        $this->client->request('POST', '/admin/article/update/12');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $this->client->request('DELETE', '/admin/article/delete/12');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context (defaults to the firewall name)
        $token = new UsernamePasswordToken('admin', null, 'main', array('ROLE_SUPER_ADMIN'));
        $session->set('_security_main', serialize($token));
        $session->save();
        $cookie = new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        return;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUserWithRoleSuperAdmin()
    {
        $userMock = $this->getMockBuilder('FOS\UserBundle\Model\UserInterface')->getMock();
        $userMock
            ->expects($this->once())
            ->method('setRoles')
            ->with(array('ROLE_SUPER_ADMIN'))
        ;
        return $userMock;
    }
}
