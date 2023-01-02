<?php

namespace App\Form\Admin;

use App\Entity\Admin\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('notes')
            ->add('price')
            ->add('hours')
            ->add('author')
            ->add('startedAt')
            ->add('finishedAt')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('services')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Statut::class,
        ]);
    }
}
