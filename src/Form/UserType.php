<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['include_pseudo']) {
            $builder->add('username', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['class' => 'input-form'],
                'row_attr' => ['class' => 'div-form'],
            ]);
        }

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'input-form'],
                'row_attr' => ['class' => 'div-form'],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'input-form'],
                'row_attr' => ['class' => 'div-form'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label'],
                'attr' => ['class' => 'btn btn-form'],
                'row_attr' => ['class' => 'div-form'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'include_pseudo' => false,
            'submit_label' => 'S\'inscrire',
        ]);
    }
}
