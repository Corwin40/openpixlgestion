<?php

namespace App\Form\Gestapp;

use App\Entity\Gestapp\Client;
use App\Entity\Gestapp\Invoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('invoiceAt', DateType::class, [
                'label'=>'Date de la facture', 
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
            ])
            ->add('num',TypeIntegerType::class,[
                'label'=>'Numero de facture',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
