<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 11/05/15
 * Time: 15:27
 */

namespace Fhm\MediaBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaTypeExtension extends AbstractTypeExtension
{
    /**
      *
      * Returns the name of the type being extended.
      *
      * @return string The name of the type being extended
    */
    public function getExtendedType()
    {
        return FileType::class;
    }

    /**
     * Add the image_path option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('image_path'));
    }
    /**
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('media_path', $options)) {
            $parentData = $form->getParent()->getData();

            if (null !== $parentData) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $mediaUrl = $accessor->getValue($parentData, $options['media_path']);
            } else {
                $mediaUrl = null;
            }
            $view->vars['media_url'] = $mediaUrl;
        }
    }
}
