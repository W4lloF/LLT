<?php

namespace App\Form;

use App\Entity\Videos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'input-form',
                    'id' => 'title',
                ],
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien',
                'attr' => [
                    'class' => 'input-form',
                    'id' => 'link',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'input-form',
                    'id' => 'description',
                ],
            ])
            ->add('access', ChoiceType::class, [
                'label' => 'Accès',
                'choices' => [
                    'Visiteurs' => 'visiteurs',
                    'Connecter' => 'connecter',
                    'Abonné' => 'abonné',
                ],
                'attr' => [
                    'class' => 'input-form',
                    'id' => 'access',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videos::class,
        ]);
    }
}
