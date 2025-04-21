<?php

namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkListType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    )
    {
        $builder
            ->setMethod('GET')
            ->add(
                child: 'search',
                type: Type\TextType::class,
                options: [
                    'label' => 'Поиск',
                    'attr' => [
                        'placeholder' => 'Введите'
                    ],
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add(
                child: 'is-author',
                type: Type\CheckboxType::class,
                options: [
                    'label' => 'Мои работы',
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add(
                child: 'sort-by',
                type: Type\ChoiceType::class,
                options: [
                    'choices' => [
                        'Название' => 'name',
                        'Дата добавления работы' => 'createdAt',
                    ],
                    'placeholder' => 'Выберите сортировку',
                    'label' => 'Сортировать',
                    'required' => false,
                ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'filter';
    }

}