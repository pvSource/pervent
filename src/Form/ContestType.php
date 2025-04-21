<?php

namespace App\Form;

use App\Entity\Contest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ContestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                child: 'slug',
                type: Type\TextType::class,
                options: [
                    'label' => 'Символьный код',
                    'attr' => [
                        'placeholder' => 'Будет сгенерировано автоматически, если оставить пустым'
                    ],
                    'required' => false,
                    'mapped' => true,
                    //'disabled' => true,
                ]
            )
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
                child: 'beginAt',
                type: Type\DateTimeType::class,
                options: [
                    'label' => 'Старт проведения конкурса',
                    'widget' => 'single_text',
                    'mapped' => true,
                    'required' => false,
                    //'input' => 'datetime_immutable',
                    'html5' => true,
                    'data' => new \DateTime(),
                ]
            )
            ->add(
                child: 'finishAt',
                type: Type\DateTimeType::class,
                options: [
                    'label' => 'Окончание проведения конкурса',
                    'widget' => 'single_text',
                    'mapped' => true,
                    'required' => false
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
            'data_class' => Contest::class,
        ]);
    }
}
