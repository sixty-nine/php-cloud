<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateCloudFormType extends AbstractType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('words', EntityType::class, array(
                'class' => 'SixtyNineCloudBundle:WordsList',
                'choice_label' => 'name',
                'required' => true,
            ))
            ->add('font', ChoiceType::class, array(
                'choices' => $options['fonts_manager']->getFontsByName(),
            ))
            ->add('color', TextType::class, array(
                'required' => true,
                'label' => 'Background color',
                'attr' => array('placeholder' => 'Color'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('fonts_manager');
    }
}
