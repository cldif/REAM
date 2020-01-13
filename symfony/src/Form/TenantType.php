<?php

namespace App\Form;

use App\Entity\Tenant;
use App\Form\PersonType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('profession')
            ->add('parent', ChoiceType::class, [
                	'label' => 'Parent',
                    'choices'  => [
                        'Père' => 1,
                        'Mère' => 2,
                        'Les deux' => 3,
                    ], 
                    'mapped' => false,
                ])
            
            ->add(
                $builder->create('father', PersonType::class, [
                        'label'    => 'Ajouter le père',
                        'required' => false,
                    ])
                    ->remove('gender')
                    ->add('gender', HiddenType::class, ['empty_data' => 'Homme'])
                )
            ->add(
                $builder->create('mother', PersonType::class, [
                        'label'    => 'Ajouter la mère',
                        'required' => false,
                    ])
                    ->remove('gender')
                    ->add('gender', HiddenType::class, ['empty_data' => 'Femme'])
                )

            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tenant::class,
        ]);
    }

    public function getParent()
    {
        return PersonType::class;
    }
}
