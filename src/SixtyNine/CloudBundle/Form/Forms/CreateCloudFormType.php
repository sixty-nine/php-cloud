<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'attr' => array(
                    'title' => 'Select the list of words to use',
                ),
            ))
            ->add('placer', ChoiceType::class, array(
                'choices' => $options['placers_manager']->getPlacersList(),
                'attr' => array(
                    'title' => 'Select how the words are placed in the cloud',
                ),
            ))
            ->add('font', ChoiceType::class, array(
                'choices' => $options['fonts_manager']->getFontsByName(),
                'attr' => array(
                    'title' => 'Select the font',
                ),
            ))
            ->add('color', TextType::class, array(
                'required' => true,
                'label' => 'Background color',
                'attr' => array(
                    'placeholder' => 'Color',
                    'title' => 'Select cloud background color',
                ),
            ))
            ->add('fontSize', ChoiceType::class, array(
                'choices' => array(
                    'Linear' => 'linear',
                    'Boost' => 'boost',
                    'Dim' => 'dim',
                ),
                'attr' => array(
                    'title' => 'Select how the font size is correlated to the occurrences of the word',
                ),
            ))
            ->add('minSize', IntegerType::class, array(
                'required' => true,
                'label' => 'Min. size',
                'data' => 10,
                'attr' => array(
                    'placeholder' => 'Size',
                    'title' => 'The minimal font size',
                ),
            ))
            ->add('maxSize', IntegerType::class, array(
                'required' => true,
                'label' => 'Max. size',
                'data' => 80,
                'attr' => array(
                    'placeholder' => 'Size',
                    'title' => 'The maximal font size',
                ),
            ))
            ->add('imageWidth', IntegerType::class, array(
                'required' => true,
                'label' => 'Image width',
                'attr' => array(
                    'placeholder' => 'Width',
                    'title' => 'The width of the cloud',
                ),
            ))
            ->add('imageHeight', IntegerType::class, array(
                'required' => true,
                'label' => 'Image height',
                'attr' => array(
                    'placeholder' => 'Height',
                    'title' => 'The height of the cloud',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('fonts_manager');
        $resolver->setRequired('placers_manager');
    }
}
