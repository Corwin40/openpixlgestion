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
                'label'=>'Nom'
            ])
            ->add('surname', TextType::class,[
                'label'=>'Prénom'
            ])
            ->add('address', TextType::class,[
                'label'=>'Adresse'
            ])
            ->add('city', TextType::class,[
                'label'=>'Ville'
            ])
            ->add('postalCode', TextType::class,[
                'label'=>'Code postal'
            ])
            ->add('phone', TextType::class,[
                'label'=>'Téléphone'
            ])
            ->add('email', TextType::class,[
                'label'=>'Email'
            ])
            ->add('members', TextType::class,[
                'label'=>'membre'
            ])
            ->add('service', TextType::class,[
                'label'=>'Service'
            ])
            ->add('typeclient', TextType::class,[
                'label'=>'Prix'
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
