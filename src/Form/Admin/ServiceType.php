<?php

namespace App\Form\Admin;

use App\Entity\Admin\Service;
use App\Entity\Admin\TypeClient;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name', EntityType::class,[
                'class' => Service::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'label'=>'Service',
                'required' => false,
                'placeholder' => 'Choisir un service'
            ])
            ->add('name', TextType::class,[
                'label'=>'Nom & archives',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom du service'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Description'
            ])
            ->add('code',TextType::class,[
                'label'=>'code',
                'required' => true,
                'attr' => [
                    'placeholder' => 'code'
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
