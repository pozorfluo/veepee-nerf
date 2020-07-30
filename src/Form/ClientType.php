<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Les champs e-mails sont différents !',
                'required' => true,
                'first_options' => ['label' => 'Email'],
                'second_options' => ['label' => 'Confirmation email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre email ne peut être vide.',
                    ]),
                ],
                ])
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'label' => false,
                'entry_options' => ['label' => false],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
