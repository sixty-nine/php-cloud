<?php

namespace SixtyNine\CloudApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WordFormType extends AbstractType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'integer', array('required' => true))
            ->add('text', 'text', array('required' => true))
            ->add('count', 'integer', array('required' => true))
            ->add('text', 'text', array('required' => true))
            ->add('orientation',  'choice', array(
                'choices'   => array('horiz' => 'horiz', 'vert' => 'vert'),
                'required'  => true,
            ))
            ->add('color', 'text', array('required' => true))
            ->add('position', 'integer', array('required' => true))
        ;
    }

    /** {@inheritDoc} */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
}
