<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;

class CloudStyleFormType extends AbstractType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sizes = array(
            10 => '10',
            12 => '12',
            14 => '14',
            16 => '16',
            18 => '18',
            20 => '20',
            30 => '30',
            40 => '40',
            50 => '50',
            60 => '60',
            70 => '70',
            80 => '80',
            90 => '90',
            100 => '100',
            120 => '120',
            150 => '150',
        );

        $builder
            ->add('palette', ChoiceType::class, array(
                'label' => 'Colors',
                'choices' => array(
                    'Aqua' => 'aqua',
                    'Yellow/Blue' => 'yellow/blue',
                    'Greyscale' => 'grey',
                    'Brown' => 'brown',
                    'Army' => 'army',
                    'Pastel' => 'pastel',
                    'Red' => 'red',
                ),
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
            ->add('minSize', ChoiceType::class, array(
                'choices' => $sizes,
                'constraints' => array(
//                    new LessThan(array('value' => $data['maxSize']))
                ),
            ))
            ->add('maxSize', ChoiceType::class, array(
                'choices' => $sizes,
                'constraints' => array(
//                    new GreaterThan(array('value' => $data['minSize']))
                ),
            ))
            ->add('orientation', ChoiceType::class, array(
                'label' => 'Dir',
                'choices' => array(
                    'Horizontal' => OrientationVisitor::WORDS_HORIZONTAL,
                    'Mainly horizontal' => OrientationVisitor::WORDS_MAINLY_HORIZONTAL,
                    'Mixed' => OrientationVisitor::WORDS_MIXED,
                    'Mainly vertical' => OrientationVisitor::WORDS_MAINLY_VERTICAL,
                    'Vertical' => OrientationVisitor::WORDS_VERTICAL,
                ),
            ))
            ->add('usher', ChoiceType::class, array(
                'choices' => array(
                    'Horizontal' => 'horiz',
                    'Vertical' => 'vert',
                    'Circular' => 'circular',
                    'Wordle' => 'wordle',
                ),
            ))
            ->add('sortby', ChoiceType::class, array(
                'choices' => array(
                    'Nothing' => 'none',
                    'Vertical first' => 'angle-v',
                    'Horizontal first' => 'angle-h',
                    'Alphabetical' => 'alpha',
                    'Size' => 'count',
                ),
            ))
            ->add('frame', CheckboxType::class, array(
                'label' => 'Show frame',
                'required' => false,
            ))
            ->add('randomColor', CheckboxType::class, array(
                'label' => 'Random color',
                'required' => false,
            ))
            ->add('debugUsher', CheckboxType::class, array(
                'label' => 'Debug usher',
                'required' => false,
            ))
        ;
    }
}
