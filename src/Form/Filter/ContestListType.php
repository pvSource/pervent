<?php

namespace App\Form\Filter;

use App\Entity\Contest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContestListType extends AbstractType
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
                    'label' => 'Мои конкурсы',
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add(
                child: 'is-participant',
                type: Type\CheckboxType::class,
                options: [
                    'label' => 'Я участвую',
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
                        'Дата начала конкурса' => 'beginAt',
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
            //'data_class' => Contest::class
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'filter';
    }

}