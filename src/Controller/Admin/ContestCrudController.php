<?php

namespace App\Controller\Admin;

use App\Entity\Contest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Конкурс')
            ->setEntityLabelInPlural('Конкурсы')
            ->setSearchFields(['name', 'code', 'description'])
            ->setDefaultSort(['beginAt' => 'DESC'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID');
        yield TextField::new('code', 'Символьный код');

        yield TextField::new('name', 'Название');
        yield TextEditorField::new('description', 'Описание');

        yield DateTimeField::new('beginAt', 'Начало')->setFormTypeOptions([
            'years' => range(date('Y'), date('Y', strtotime('+1 year'))),
            'widget' => 'single_text',
        ]);

        yield DateTimeField::new('finishAt', 'Завершение')->setFormTypeOptions([
            'years' => range(date('Y'), date('Y', strtotime('+1 year'))),
            'widget' => 'single_text',
        ]);
    }
}
