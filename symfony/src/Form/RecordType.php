<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Record;
use App\Entity\Tenant;

use App\Form\PersonType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'widget' => 'single_text',
                    'by_reference' => true,
                ])
            ->add('rent', NumberType::class)
            ->add('fixedCharge', NumberType::class)
            ->add('periodicity', TextType::class)
            ->add('revisionIndex', TextType::class)
            ->add('releaseDate', DateType::class, [
                    'widget' => 'single_text',
                    'by_reference' => true,
                ])
            ->add('signingDate', DateType::class, [
                    'widget' => 'single_text',
                    'by_reference' => true,
                ])
            ->add('additionalInformation', TextType::class,[
                    'required' => false,
                ])
            ->add('tenant', EntityType::class, [
                'class' => Tenant::class,
                'choice_label' => 'name',
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'name',
            ])
            ->add('guarantorChoice', ChoiceType::class, [
                	'label' => 'Choix du garant',
                    'choices'  => [
                        'Père' => 1,
                        'Mère' => 2,
                        'Autre' => 3,
                    ], 
                    'mapped' => false,
                ])
            
            ->add('guarantor', PersonType::class, [
                        'label'    => 'Ajouter le garant',
                        'required' => false,
                ])
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
