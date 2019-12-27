<?php

namespace App\Form;

use App\Entity\Tenant;
use App\Form\PersonType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'Père' => 'Pere',
                    'Mère' => 'Mere',
                    'Les deux' => 'Les deux',
                ], 'mapped' => false])
            ->add('father', PersonType::class, [
                    'label'    => 'Ajouter le père',
                    'required' => false
                ])
            ->add('mother', PersonType::class, [
                    'label'    => 'Ajouter la mère',
                    'required' => false
                ])
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
