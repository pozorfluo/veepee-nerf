<?php

namespace App\Form;

use App\Entity\OrderInfo;
use App\Entity\Product;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                'required' => true,
            ])
            ->add('stripe', SubmitType::class, [
                'label' => 'Payer par carte bancaire',
                'attr' => ['class' => 'waves-effect waves-light btn'],
            ])
            ->add('paypal', SubmitType::class, [
                'label' => 'Payer avec Paypal',
                'attr' => ['class' => 'waves-effect waves-light btn'],
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    /**
                     * If delivery address is untouched, clone billing
                     * address before form handling and validation.
                     * 
                     * https://symfony.com/doc/current/form/events.html
                     */
                    $data = $event->getData();

                    $billingAddress = &$data['client']['addresses'][0];
                    $deliveryAddress = &$data['client']['addresses'][1];

                    if (empty(implode("", $deliveryAddress))) {
                        $deliveryAddress = $billingAddress;
                    }
                    $event->setData($data);
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderInfo::class,
        ]);
    }
}
