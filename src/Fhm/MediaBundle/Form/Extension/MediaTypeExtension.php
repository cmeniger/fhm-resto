<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 11/05/15
 * Time: 15:27
 */

namespace Fhm\MediaBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaTypeExtension extends AbstractTypeExtension
{

    public function getExtendedType()
    {
        return 'file';
    }

    /**
     * Add image_path
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('media_path'));
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
