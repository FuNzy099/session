<?php

namespace App\Form;

use App\Entity\Atelier;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AjouterAtelierType extends AbstractType
{
    // buildForm est une méthode qui permet de construire le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)       // TextType, c'est l'equivalent de <input type="text"> il indique que ce champs est de type text
            ->add('submit', SubmitType::class)  // SubmitType, c'est l'equivalent de <input type="submit">, il sert à valider le formulaire
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Atelier::class,
        ]);
    }
}
