<?php

namespace App\Form\Admin;

use App\Entity\Gestapp\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => "Nom de l'intervention"
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Description",
                ]
            ])
            ->add('startedAt', TimeType::class,[
                'label'=>'Début / Fin',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('finishedAt', TimeType::class,[
                'label'=>'',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('isRecurr', CheckboxType::class,[
                'label' => 'Mettre en place la récurence ?',
                'required' => false
            ])
            ->add('recurrence', ChoiceType::class,[
                'label'=>'Récurrence',
                'required' => false,
                'choices'  => [
                    'Toutes les semaines' => '1',
                    'Toutes les 2 semaines' => '2',
                    'Une fois par mois' => '4',
                    'Tous les 2 mois' => '8',
                    'une fois par trimestre' => '12',
                ]
            ])
            ->add('multiple', ChoiceType::class,[
                'label'=>'Répétition',
                'required' => false,
                'choices'  => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
