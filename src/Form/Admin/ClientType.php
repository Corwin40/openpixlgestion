<?php

namespace App\Form\Admin;

use App\Entity\Gestapp\Client;
use App\Entity\Gestapp\TypeClient;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logoFile', FileType::class, [
            'label' => 'Logo',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '10000k',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpg',
                        'image/jpeg',
                        ],
                    'mimeTypesMessage' => 'Attention, veuillez charger un fichier au format jpg ou png',
                    ])
                ],
            ])
            ->add('isSupprLogo', CheckboxType::class,[
                'label' => 'Supprimer le logo',
                'required' => false
                ])
            ->add('firstName', TextType::class,[
                'label'=>'Prénom & Nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez le nom'
                ]
            ])
            ->add('lastName', TextType::class,[
                'label'=>'Nom',
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
                'choice_attr' => function (TypeClient $typeClient, $key, $index) {
                    return [
                        'data-data' => $typeClient->getName(),
                    ];
                },
                'label'=>'Type de client',
                'required' => false,
                'placeholder' => 'Choisir un type de client',
                'attr' => [
                    'form_attr' => true,
                ]
            ])
            ->add('nameStructure', TextType::class,[
                'label'=>'Nom de la société',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom de la société'
                ]
            ])
            ->add('surnameStructure', TextType::class,[
                'label'=>'Nom court de la société',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom de la société'
                ]
            ])
            ->add('director', TextType::class,[
                'label'=>'Directeur',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom du directeur'
                ]
            ])
            ->add('siren', TextType::class,[
                'label'=>'N°Siren | N°Siret',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro de Siren'
                ]
            ])
            ->add('siret', TextType::class,[
                'label'=>'N°Siret',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Numéro de Siret'
                ]
            ])
            ->add('tva', TextType::class,[
                'label'=>'TVA',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Taux TVA'
                ]
            ])
            ->add('activityPro', TextType::class,[
                'label'=>'Activité',
                'required' => false,
                'attr' => [
                    'placeholder' => "Entrez l'activité de l'entreprise"
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
