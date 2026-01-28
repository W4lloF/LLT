<?php

namespace App\Form;

use App\Entity\Coach;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('coach', EntityType::class, [
                'class' => Coach::class,
                'choice_label' => 'pseudo',
                'attr' => ['class' => 'input-form'],
            ])

            ->add('month', ChoiceType::class, [
                'choices' => [
                    'Janvier' => 1,
                    'Février' => 2,
                    'Mars' => 3,
                    'Avril' => 4,
                    'Mai' => 5,
                    'Juin' => 6,
                    'Juillet' => 7,
                    'Aout' => 8,
                    'Septembre' => 9,
                    'Octobre' => 10,
                    'Novembre' => 11,
                    'Decembre' => 12,

                ],
                'mapped' => false,
                'attr' => ['class' => 'input-form'],
            ])

            ->add('day', ChoiceType::class, [
                'choices' => array_combine(range(1, 31), range(1, 31)),
                'mapped' => false,
                'attr' => ['class' => 'input-form'],
            ])

            ->add('time', ChoiceType::class, [
                'choices' => [
                    '12h00' => '13:00',
                    '13h00' => '13:00',
                    '14h00' => '14:00',
                    '15h00' => '15:00',
                    '16h00' => '16:00',
                    '17h00' => '17:00',
                    '18h00' => '18:00',
                    '19h00' => '19:00',
                    '20h00' => '20:00',
                    '21h00' => '21h00',
                ],
                'mapped' => false,
                'attr' => ['class' => 'input-form'],
            ])

            ->add('coaching_type', ChoiceType::class, [
                'choices' => [
                    'Gratuit' => 'gratuit',
                    'Payant' => 'payant',
                ],
                'mapped' => false,
                'attr' => ['class' => 'input-form'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
