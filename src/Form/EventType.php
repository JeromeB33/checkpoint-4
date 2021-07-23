<?php

namespace App\Form;

use App\Entity\Event;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => false])
            ->add('synopsis', TextareaType::class, ['label' => false])
            ->add('description', CKEditorType::class, ['label' => false])
            ->add('game', TextType::class, ['label' => false])
            ->add('date', DateTimeType::class, ['label' => false])
            ->add('duration', IntegerType::class, ['label' => false])
            ->add('isStreamed', CheckboxType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('playerSlot', IntegerType::class, ['label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
