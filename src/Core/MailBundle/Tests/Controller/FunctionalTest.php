<?php

namespace Core\MailBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
    public function testShow()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/mail');
        $this->assertGreaterThan(
            0,
            $crawler->filter('body:contains("mail")')->count()
        );
    }
}
