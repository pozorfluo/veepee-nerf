<?php

namespace App\Form;

use App\Entity\OrderInfo;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', ClientType::class)
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'expanded' => true,
                'multiple' => false,
                'choice_label' => 'description',
                'choice_value' => 'id',
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'choices' => ['stripe', 'paypal']
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderInfo::class,
        ]);
    }
}
