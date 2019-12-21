<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\Record;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supposedDate', DateType::class, [
                    'widget' => 'choice',
                ])
            ->add('paidDate', DateType::class, [
                    'widget' => 'choice',
                ])
            ->add('state', ChoiceType::class, [
                'choices'  => [
                    'Payé' => 'Payé',
                    'Non payé' => 'Non payé',
                    'Absent' => 'Absent',
                ]])
            ->add('amount', NumberType::class)
            ->add('record', EntityType::class, [
                'class' => Record::class,
                'choice_label' => 'id',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
