<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use SixtyNine\Cloud\Filters\ChangeCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class FiltersFormType extends AbstractType
{
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array('placeholder' => 'URL')
            ))
            ->add('changeCaseEnabled', CheckboxType::class, array(
                'label'    => 'Enable change case filter?',
                'required' => false,
                'attr' => array('checked'   => 'checked'),
            ))
            ->add('case', ChoiceType::class, array(
                'label' => false,
                'choices' => array(
                    ChangeCase::LOWERCASE => 'Lowercase',
                    ChangeCase::UPPERCASE => 'Uppercase',
                    ChangeCase::UCFIRST   => 'Uppercase first',
                ),
            ))
            ->add('removeNumbersEnabled', CheckboxType::class, array(
                'label'    => 'Enable remove numbers filter?',
                'required' => false,
                'attr' => array('checked' => 'checked'),
            ))
            ->add('removeUnwantedCharEnabled', CheckboxType::class, array(
                'label'    => 'Enable remove unwanted characters filter?',
                'required' => false,
                'attr' => array('checked' => 'checked'),
            ))
            ->add('removeTrailingCharEnabled', CheckboxType::class, array(
                'label'    => 'Enable remove trailing characters filter?',
                'required' => false,
                'attr' => array('checked' => 'checked'),
            ))
            ->add('removeByLengthEnabled', CheckboxType::class, array(
                'label'    => 'Enable remove characters by length filter?',
                'required' => false,
            ))
            ->add('minLength', TextType::class, array(
                'label' => false,
                'required' => false,
                'attr' => array('placeholder' => 'Minimal len')
            ))
            ->add('maxLength', TextType::class, array(
                'label' => false,
                'required' => false,
                'attr' => array('placeholder' => 'Maximal len')
            ))
        ;
    }
}
