<?php

namespace App\Form\Admin;

use App\Entity\Admin\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('startedAt', TimeType::class,[
                'label'=>'Début / Fin',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('finishedAt', TimeType::class,[
                'label'=>'',
                'required' => false,
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
