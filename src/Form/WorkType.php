<?php

namespace App\Form;

use App\Entity\Contest;
use App\Entity\Work;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                child: 'name',
                type: Type\TextType::class,
                options: [
                    'label' => 'Название',
                    'required' => true,
                    'mapped' => true,
                    'disabled' => false,
                ]
            )
            ->add(
                child: 'description',
                type: Type\TextareaType::class,
                options: [
                    'label' => 'Описание',
                    'required' => true,
                    'mapped' => true,
                    'disabled' => false,
                ]
            )
            ->add(
                child: 'contest',
                type: EntityType::class,
                options: [
                    'class' => Contest::class,
                    'choice_label' => 'slug', // или 'id' — не критично, поле всё равно скрыто
                    'data' => $options['data']->getContest(), // ВАЖНО: передаём именно объект Contest
                    'attr' => ['style' => 'display:none;'],   // Скрываем в шаблоне
                    'label' => false,
                ]
            )
            ->add(
                child: 'image',
                type: Type\FileType::class,
                options: [
                    'label' => 'Изображение',
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new Image(['maxSize' => '4096K'])
                    ]
                ]
            )
            ->add(
                child: 'submit',
                type: Type\SubmitType::class,
                options: [
                    'label' => 'Сохранить'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Work::class,
        ]);
    }
}
