<?php

namespace App\Form;

use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => false])
            ->add('lastname', TextType::class, ['label' => false])
            ->add('firstname', TextType::class, ['label' => false])
            ->add('description', CKEditorType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('email', EmailType::class, ['label' => false])
            ->add('discord', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('twitchChannel', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('city', TextType::class, ['label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
