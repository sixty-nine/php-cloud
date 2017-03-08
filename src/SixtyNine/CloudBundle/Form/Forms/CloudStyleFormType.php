<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('text', 'textarea', array(
                'required' => false,
                'attr' => array('placeholder' => 'Text')
            ))
            ->add('url', 'text', array(
                'required' => false,
                'attr' => array('placeholder' => 'URL')
            ))
            ->add('palette', 'choice', array(
                'label' => 'Colors',
                'choices' => array(
                    'aqua' => 'Aqua',
                    'yellow/blue' => 'Yellow/Blue',
                    'grey' => 'Greyscale',
                    'brown' => 'Brown',
                    'army' => 'Army',
                    'pastel' => 'Pastel',
                    'red' => 'Red',
                ),
            ))
            ->add('font', 'choice', array(
                'choices' => array(
                    'Airmole_Antique.ttf' => 'Airmole Antique',
                    'Airmole_Shaded.ttf' => 'Airmole Shaded',
                    'Alexis_3D.ttf' => 'Alexis 3D',
                    'Almonte_Snow.ttf' => 'Almonte Snow',
                    'Arial.ttf' => 'Arial',
                    'Paper_Cut.ttf' => 'Paper Cut',
                    'TheThreeStoogesFont.ttf' => 'The Three Stooges',
                    'Marcsc___.ttf' => 'Marcelle',
                    'SoulMission.ttf' => 'SoulMission',
                    'FAIL.ttf' => 'FAIL',
                    'laundromat_1967.ttf' => 'Laundromat 1967',
                    'KILLEDDJ.ttf' => 'Killed DJ',
                ),
            ))
            ->add('minSize', 'choice', array(
                'choices' => $sizes,
                'constraints' => array(
//                    new LessThan(array('value' => $data['maxSize']))
                ),
            ))
            ->add('maxSize', 'choice', array(
                'choices' => $sizes,
                'constraints' => array(
//                    new GreaterThan(array('value' => $data['minSize']))
                ),
            ))
            ->add('orientation', 'choice', array(
                'label' => 'Dir',
                'choices' => array(
                    OrientationVisitor::WORDS_HORIZONTAL => 'Horizontal',
                    OrientationVisitor::WORDS_MAINLY_HORIZONTAL => 'Mainly horizontal',
                    OrientationVisitor::WORDS_MIXED => 'Mixed',
                    OrientationVisitor::WORDS_MAINLY_VERTICAL => 'Mainly vertical',
                    OrientationVisitor::WORDS_VERTICAL => 'Vertical',
                ),
            ))
            ->add('usher', 'choice', array(
                'choices' => array(
                    'horiz' => 'Horizontal',
                    'vert' => 'Vertical',
                    'circular' => 'Circular',
                    'wordle' => 'Wordle',
                ),
            ))
            ->add('sortby', 'choice', array(
                'choices' => array(
                    'none' => 'Nothing',
                    'angle-v' => 'Vertical first',
                    'angle-h' => 'Horizontal first',
                    'alpha' => 'Alphabetical',
                    'count' => 'Size',
                ),
            ))
            ->add('frame', 'checkbox', array(
                'label' => 'Show frame',
                'required' => false,
            ))
            ->add('randomColor', 'checkbox', array(
                'label' => 'Random color',
                'required' => false,
            ))
            ->add('debugUsher', 'checkbox', array(
                'label' => 'Debug usher',
                'required' => false,
            ))
        ;
    }
}
