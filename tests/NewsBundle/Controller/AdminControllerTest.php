<?php

namespace Tests\Controller;

use Fhm\NewsBundle\Controller\AdminController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function getMockSecurityContext($expectedUser)
    {
        $token = $this->getMockBuilder('Symfony\Component\Core\Authentication\Token\TokenInterface')->getMock();
        $token->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($expectedUser));

        $securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token));

        return $securityContext;
    }

    public function createArticle()
    {
        $user = $this->getMockBuilder('Fhm\UserBundle\Document\User')->getMock();
        $securityContext = $this->getMockSecurityContext($user);
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

        $controller = new AdminController();
        $response = $controller->createAction($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
