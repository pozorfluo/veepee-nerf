<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\DeliveryAddress;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('addressComplement')
            ->add('city')
            ->add('zipCode')
            ->add('phone')
            // ->add('client')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                // 'choice_label' => 'id',
                // 'choice_value' => 'name',
                // 'choice_attr' => 'name',
                // 'data' => 'name',
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeliveryAddress::class,
        ]);
    }
}
