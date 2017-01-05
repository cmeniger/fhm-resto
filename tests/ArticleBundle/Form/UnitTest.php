<?php

namespace tests\Form;

use Fhm\ArticleBundle\Controller\AdminController;
use Fhm\ArticleBundle\Document\Article;
use Fhm\ArticleBundle\Form\Type\Admin\CreateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

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
    public function testCreateFormArticle()
    {
        $form = $this->factory->create(CreateType::class, new Article());

        $this->assertTrue(!is_null($form) ? true : false);
    }

    public function testSubmitCreateFormArticle()
    {
        $article = new Article();
        $form = $this->factory->create(CreateType::class, $article);

        $formData = array(
            'title' => 'test',
            'subtitle' => 'test',
            'content' => 'test',
            'resume' => 'test',
        );
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($article, $form->getData());
        $this->assertSame('test', $article->getTitle());
    }
}
