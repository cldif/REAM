<?php

namespace App\Form;

use App\Entity\Record;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entryDate', DateType::class, [
                    'widget' => 'choice',
                ])
            ->add('rent', NumberType::class)
            ->add('fixedCharge', NumberType::class)
            ->add('periodicity', TextType::class)
            ->add('revisionIndex', TextType::class)
            ->add('releaseDate', DateType::class, [
                    'widget' => 'choice',
                ])
            ->add('additionalInformation', TextType::class,[
                    'required' => false,
                ])
            ->add('files', FileType::class, [
                'mapped' => false,
                'multiple' => true,
            ])
            //->add('tenant')
            //->add('local')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
        ]);
    }
}
