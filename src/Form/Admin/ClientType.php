<?php

namespace App\Form\Admin;

use App\Entity\Admin\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom & prénom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le nom'
                ]
            ])
            ->add('surname', TextType::class,[
                'label'=>'Prénom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le prénom'
                ]
            ])
            ->add('address', TextType::class,[
                'label'=>'Adresse',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('city', TextType::class,[
                'label'=>'Ville',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('postalCode', TextType::class,[
                'label'=>'CP / Ville',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Code postal'
                ]
            ])
            ->add('phone', TextType::class,[
                'label'=>'Téléphone',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone'
                ]
            ])
            ->add('email', TextType::class,[
                'label'=>'Email',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
