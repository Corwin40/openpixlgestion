<?php

namespace App\Form\Admin;

use App\Entity\Admin\FicheService;
use App\Entity\Admin\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', EntityType::class,[
                'class' => Service::class,
                'label'=>'Nom du service',
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
                'label'=>'Tarif de base',
                'required' => false,
                'choices'  => [
                    '100 €' => '100',
                    "200 €" => "200",
                    '300 €' => '300',
                    "400 €" => "400",
                    "500 €" => "500",
                ]
            ])
            ->add('statut', ChoiceType::class,[
                'label'=>'Etat du service',
                'required' => false,
                'choices'  => [
                    'service actif' => 'service actif',
                    "service proche de l'échéance" => "service proche de l'échéance",
                    'service à échéance' => 'service à échéance',
                ],

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
