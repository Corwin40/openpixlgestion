<?php

namespace App\Form\Admin;

use App\Entity\Admin\FicheService;
use App\Entity\Admin\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'attr' => [
                    'placeholder' => 'Entrez le nom du service'
                ]
            ])
            ->add('time', ChoiceType::class,[
                'label'=>'Temps',
                'required' => false,
                'placeholder' =>'Veuillez entrez une durée',
                'choices'  => [
                    '1 an' => '1 an',
                    '2 ans' => '2 ans',
                    '3 ans' => '3 ans',
                    '4 ans' => '4 ans',
                    '5 ans' => '5 ans',
                ]
            ])
            ->add('price', ChoiceType::class,[
                'label'=>'Prix',
                'required' => false,
                'placeholder' =>'Veuillez entrez une durée',
                'choices'  => [
                    '100 €' => '100 €',
                    "200 €" => "200 €",
                    '300 €' => '300 €',
                    "400 €" => "400 €",
                    '500 €' => '500 €',
                ]
            ])
            ->add('statut', ChoiceType::class,[
                'label'=>'Statut',
                'required' => false,
                'placeholder' =>'Veuillez entrez une durée',
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
