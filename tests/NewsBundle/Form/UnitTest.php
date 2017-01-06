<?php

namespace tests\Form;

use Fhm\NewsBundle\Document\News;
use Symfony\Component\Form\Test\TypeTestCase;

/*
A unit test is a test against a single PHP class, also called a unit. If you want to test the overall behavior of your application, see the Functional Tests.
assertTrue/AssertFalse:	Check the input to verify it equals true/false
assertEquals:           Check the result against another input for a match
assertGreaterThan:      Check the result to see if it’s larger than a value (there’s also LessThan, GreaterThanOrEqual, and LessThanOrEqual)
assertContains:         Check that the input contains a certain value
assertType:             Check that a variable is of a certain type
assertNull:             Check that a variable is null
assertFileExists:       Verify that a file exists
assertRegExp:           Check the input against a regular expression
*/
class UnitTest extends TypeTestCase
{
    public function testCreateFormNews()
    {
        $form = $this->factory->create(\Fhm\NewsBundle\Form\Type\Admin\CreateType::class, new News());

        $this->assertTrue(!is_null($form) ? true : false);
    }

    public function testSubmitCreateFormNews()
    {
        $news = new News();
        $form = $this->factory->create(\Fhm\NewsBundle\Form\Type\Admin\CreateType::class, $news);

        $formData = array(
            'title' => 'test',
            'subtitle' => 'test',
            'content' => 'test',
            'resume' => 'test',
        );
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($news, $form->getData());
        $this->assertSame('test', $news->getTitle());
    }
}
