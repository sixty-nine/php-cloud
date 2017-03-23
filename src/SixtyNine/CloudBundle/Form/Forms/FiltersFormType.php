<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use SixtyNine\Cloud\Filters\ChangeCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class FiltersFormType extends AbstractType
{
    public function getBlockPrefix() {
        return '';
    }
    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('changeCaseEnabled', CheckboxType::class, array(
                'label'    => 'Change case',
                'required' => false,
                'attr' => array('checked'   => 'checked'),
            ))
            ->add('case', ChoiceType::class, array(
                'label' => false,
                'choices' => array(
                    'Lowercase' => ChangeCase::LOWERCASE,
                    'Uppercase' => ChangeCase::UPPERCASE,
                    'Uppercase first' => ChangeCase::UCFIRST,
                ),
            ))
            ->add('removeNumbersEnabled', CheckboxType::class, array(
                'label'    => 'Remove numbers',
                'required' => false,
                'attr' => array('checked' => 'checked'),
            ))
            ->add('removeUnwantedCharEnabled', CheckboxType::class, array(
                'label'    => 'Remove unwanted characters',
                'required' => false,
                'attr' => array('checked' => 'checked'),
            ))
            ->add('removeTrailingCharEnabled', CheckboxType::class, array(
                'label'    => 'Remove trailing characters',
                'required' => false,
                'attr' => array('checked' => 'checked'),
            ))
            ->add('removeByLengthEnabled', CheckboxType::class, array(
                'label'    => 'Remove characters by length',
                'required' => false,
                'attr' => array('checked' => 'checked'),
            ))
            ->add('minLength', IntegerType::class, array(
                'label' => false,
                'required' => false,
                'data' => 4,
                'attr' => array('placeholder' => 'Minimal len')
            ))
            ->add('maxLength', IntegerType::class, array(
                'label' => false,
                'required' => false,
                'data' => 15,
                'attr' => array('placeholder' => 'Maximal len')
            ))
        ;
    }
}
