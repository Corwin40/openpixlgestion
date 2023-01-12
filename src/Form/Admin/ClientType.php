<?php

namespace App\Form\Admin;

use App\Entity\Admin\Client;
use App\Entity\Admin\TypeClient;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->add('email', EmailType::class,[
                'label'=>'Email',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('typeclient', EntityType::class,[
                'class' => TypeClient::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'label'=>'Type de client',
                'required' => false,
                'placeholder' => 'Choisir un type de client'
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
