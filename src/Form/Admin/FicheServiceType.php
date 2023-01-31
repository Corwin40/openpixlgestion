<?php

namespace App\Form\Admin;

use App\Entity\Admin\FicheService;
use App\Entity\Admin\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('time', TextType::class,[
                'label'=>'Temps',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le temps passé dessus'
                ]
            ])
            ->add('price', TextType::class,[
                'label'=>'Prix',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez le prix'
                ]
            ])
            ->add('statut', TextType::class,[
                'label'=>'Statut',
                'required' => false,
                'attr' => [
                    'placeholder' => "entrez le statut de l'article statut"
                ]
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
