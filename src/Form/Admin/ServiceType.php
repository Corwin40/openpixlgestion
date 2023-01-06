<?php

namespace App\Form\Admin;

use App\Entity\Admin\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom & archives',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom du service'
                ]
            ])
            ->add('archives', TextType::class,[
                'required' => true,
                'attr' => [
                    'placeholder' => "Nom de l'archive"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
