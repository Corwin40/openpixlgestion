<?php

namespace App\Form\Admin;

use App\Entity\Admin\Choice\TypoServ;
use App\Entity\Gestapp\Service;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
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
                'label'=>'Nom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom du service'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Description',
                'required' => false,
            ])
            ->add('code',TextType::class,[
                'label'=>'code',
                'required' => true,
                'attr' => [
                    'placeholder' => 'code'
                ]
            ])
            ->add('duration', TimeType::class,[
                'label'=>"volume d'heure",
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('typoServ', EntityType::class,[
                'class' => TypoServ::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'label'=>'Type de service',
                'required' => false,
                'placeholder' => 'Choisir quel type'
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Rendre le service actif',
                'required' => false
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
