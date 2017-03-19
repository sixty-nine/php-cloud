<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateCloudFormType extends AbstractType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Do we really need a cloud to have a name ?
            ->add('name', TextType::class, array(
                'required' => true,
                'attr' => array('placeholder' => 'Cloud name')
            ))
            ->add('font', ChoiceType::class, array(
                'choices' => array(
                    'Airmole Antique' => 'Airmole_Antique.ttf',
                    'Airmole Shaded' => 'Airmole_Shaded.ttf',
                    'Alexis 3D' => 'Alexis_3D.ttf',
                    'Almonte Snow' => 'Almonte_Snow.ttf',
                    'Arial' => 'Arial.ttf',
                    'Paper Cut' => 'Paper_Cut.ttf',
                    'The Three Stooges' => 'TheThreeStoogesFont.ttf',
                    'Marcelle' => 'Marcsc___.ttf',
                    'SoulMission' => 'SoulMission.ttf',
                    'FAIL' => 'FAIL.ttf',
                    'Laundromat 1967' => 'laundromat_1967.ttf',
                    'Killed DJ' => 'KILLEDDJ.ttf',
                ),
            ))
            ->add('color', TextType::class, array(
                'required' => true,
                'attr' => array('placeholder' => 'Color')
            ))
            ->add('words', EntityType::class, array(
                'class' => 'SixtyNineCloudBundle:WordsList',
                'choice_label' => 'name',
                'required' => true,

            ))
        ;
    }
}
