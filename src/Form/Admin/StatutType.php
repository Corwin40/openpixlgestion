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
                'label'=>'Nom'
            ])
            ->add('notes', TextType::class,[
                'label'=>'Notes'
            ])
            ->add('price', TextType::class,[
        'label'=>'Prix'
    ])
            ->add('hours', TextType::class,[
        'label'=>'Heures'
    ])
            ->add('author', TextType::class,[
        'label'=>'Auteur'
    ])
            ->add('startedAt', TextType::class,[
                'label'=>'Début le'
            ])
            ->add('finishedAt', TextType::class,[
                'label'=>'Fini le'
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
