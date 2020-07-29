<?php

namespace App\Form;

use App\Entity\OrderInfo;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class OrderInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', ClientType::class, [
            ])
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
            ->add('paypal', SubmitType::class, [
                'attr' => ['class' => 'waves-effect waves-light btn'],
            ])
            ->add('stripe', SubmitType::class, [
                'attr' => ['class' => 'waves-effect waves-light btn'],
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var OrderInfo $orderInfo */
                    $orderInfo = $event->getData();
                    dump($event);
                    $delivery = $orderInfo->getClient()->getAddresses();
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
