<?php

namespace App\Form\Admin;

use App\Entity\Admin\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom',
                'required' => true,
                'attr' => [
                    'placeholder' => "Nom de l'archives"
                ]
            ])
            ->add('notes', TextType::class,[
                'label'=>'Notes',
                'required' => false,
                'attr' => [
        'placeholder' => "Notes de mise à jour"
    ]
            ])
            ->add('price', TextType::class,[
                'label'=>'Prix',
                'required' => true,
                'attr' => [
        'placeholder' => "Prix de la tâche"
    ]
            ])
            ->add('hours', TextType::class,[
                'label'=>'Heures',
                'required' => true,
                'attr' => [
                    'placeholder' => "Heures passées"
                ]
            ])
            ->add('author', TextType::class,[
                'label'=>'Auteur',
                'required' => true,
                'attr' => [
        'placeholder' => "Nom de l'auteur"
    ]
            ])
            ->add('startedAt', TextType::class,[
                'label'=>'Début le',
                'required' => true,
                'attr' => [
                    'placeholder' => "Date de début"
                ]
            ])
            ->add('finishedAt', TextType::class,[
                'label'=>'Fini le',
                'required' => false,
                'attr' => [
        'placeholder' => "Date de fin"
    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Statut::class,
        ]);
    }
}
