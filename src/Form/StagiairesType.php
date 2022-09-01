<?php

namespace App\Form;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('nom')
            ->add('prenom')
            ->add('dateDeNaissance')
            ->add('ville')
            ->add('email')
            ->add('telephone')
            // ->add('sessions', EntityType::class, [
            //     'class' => Session::class,
            //     'choice_label' => 'lieu',
            
            //     'mapped' => false
            // ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
