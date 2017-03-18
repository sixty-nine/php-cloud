<?php

namespace SixtyNine\CloudApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WordFormType extends AbstractType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, array('required' => true))
            ->add('text', TextType::class, array('required' => true))
            ->add('count', IntegerType::class, array('required' => true))
            ->add('orientation',  ChoiceType::class, array(
                'choices'   => array('horiz' => 'horiz', 'vert' => 'vert'),
                'required'  => true,
            ))
            ->add('color', TextType::class, array('required' => true))
            ->add('position', IntegerType::class, array('required' => true))
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
