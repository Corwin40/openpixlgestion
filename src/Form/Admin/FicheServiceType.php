<?php

namespace App\Form\Admin;

use App\Entity\Gestapp\FicheService;
use App\Entity\Gestapp\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du service'
            ])
            ->add('descriptif', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Description"
                ]
            ])
            ->add('service', EntityType::class,[
                'class' => Service::class,
                'label'=>'Service',
                'required' => true,
            ])
            ->add('engagement', DateIntervalType::class, [
                'label' => "Durée de l'engagement",
                'widget' => 'choice',
                'with_years'  => true,
                'with_months' => false,
                'with_days'   => false,
                'with_hours'  => false,
                'years' => array_combine(range(1, 5), range(1, 5)),
                'labels' => ['years' => 'Année']
            ])
            ->add('package', ChoiceType::class,[
                'label'=>'Tarif du forfait',
                'required' => false,
                'choices'  => [
                    '0 €' => '0',
                    '100 €' => '100',
                    "200 €" => "200",
                    '300 €' => '300',
                    "400 €" => "400",
                    "500 €" => "500",
                ]
            ])
            ->add('priceBundle', TextType::class,[
                'label'=>'Tarif du forfait adaptée',
            ])
            ->add('priceHour', TextType::class,[
                'label'=>'Tarif à l heure',
            ])
            ->add('tva', ChoiceType::class,[
                'label'=>'tva',
                'required' => false,
                'choices'  => [
                    '0 %' => 0,
                    "5 %" => 5,
                    '15 %' => 15,
                    "20 %" => 20,
                ],
            ])
            
            ->add('statut', ChoiceType::class,[
                'label'=>'Etat du service',
                'required' => true,
                'choices'  => [
                    'prestation' => [
                        'prestation en cours' => 'prestation en cours',
                        'prestation close' => 'prestation close',
                    ],
                    'Service' => [
                        'service actif' => 'service actif',
                        "service proche de l'échéance" => "service proche de l'échéance",
                        'service à échéance' => 'service à échéance',
                    ]
                ],
            ])
            ->add('choicePrice', ChoiceType::class, [
                'label' => 'Mode de règlement',
                'attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => [
                    'Au forfait fixe' => 0,
                    "Au forfait adaptée" => 1,
                    "A l'heure" => 2
                ],
                'expanded' => true,
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheService::class,
        ]);
    }
}
