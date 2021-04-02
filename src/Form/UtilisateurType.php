<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identifiant', TextType::class,
                ['label' => 'Identifiant', 'attr' => ['placehodler' => 'login']])
            ->add('motdepasse', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'=> ['label' => 'Mot de passe', 'attr' => ['placehodler' => 'password'],'help'=>'Le mot de passe doit contenir 6 caractères minimum.'],
                'second_options'=> ['label' => 'Confirmer le mot de passe', 'attr' => ['placehodler' => 'password'],'help'=>'Le mot de passe doit contenir 6 caractères minimum.']
            ])
            ->add('nom', TextType::class,
                ['label' => 'Nom','required'=>false])
            ->add('prenom', TextType::class,
                ['label' => 'Prénom','required'=>false])
            ->add('anniversaire', BirthdayType::class,
                ['label' => 'Date de naissance', 'widget'=>'choice','required'=>false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
