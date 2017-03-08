<?php

namespace SixtyNine\CloudBundle\Form\Forms;

use SixtyNine\Cloud\Filters\ChangeCase;
use Symfony\Component\Form\AbstractType;
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
            ->add('case', ChoiceType::class, array(
                'choices' => array(
                    ChangeCase::LOWERCASE => 'Lowercase',
                    ChangeCase::UPPERCASE => 'Uppercase',
                    ChangeCase::UCFIRST   => 'Uppercase first',
                ),
            ))
            ->add('enableChangeCase', ChoiceType::class, array(
                'required' => false,
                'label' => 'Enabled?'
            ))
            ->add('nyLength', ChoiceType::class, array(
                'choices' => array(
                    ChangeCase::LOWERCASE => 'Lowercase',
                    ChangeCase::UPPERCASE => 'Uppercase',
                    ChangeCase::UCFIRST   => 'Uppercase first',
                ),
            ))
            ->add('enableChangeCase', ChoiceType::class, array(
                'required' => false,
                'label' => 'Enabled?'
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
            ->add('enableByLength', ChoiceType::class, array(
                'required' => false,
                'label' => 'Enabled?'
            ))
        ;
    }
}
