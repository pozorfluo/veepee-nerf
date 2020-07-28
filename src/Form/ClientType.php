<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Les champs e-mails sont différents !',
                'required' => true,
                'first_options' => ['label' => 'Email'],
                'second_options' => ['label' => 'Confirmation email']
            ])
            ->add('address')
            ->add('addressComplement')
            ->add('city')
            ->add('phone')
            // ->add('createdAt')
            ->add('zipCode')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'placeholder' => 'Choisissez un pays',
                'expanded' => true,
                'multiple' => false,
            ])
            // ->add('deliveryAddress', DeliveryAddressType::class)<s
            // ->add('orderInfo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
