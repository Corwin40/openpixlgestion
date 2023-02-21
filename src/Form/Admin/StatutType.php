<?php

namespace App\Form\Admin;

use App\Entity\Admin\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
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
                    'placeholder' => "Nom de l'archive"
                ]
            ])
            ->add('notes', TextareaType::class,[
                'label'=>'Notes',
                'required' => false,
                'attr' => [
                    'placeholder' => "Notes de mise à jour"
                ]
            ])
            ->add('startedAt', TimeType::class,[
                'label'=>'Début / Fin',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('finishedAt', TimeType::class,[
                'label'=>'Fin',
                'required' => false,
                'widget' => 'single_text',
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
