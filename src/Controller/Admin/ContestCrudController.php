<?php

namespace App\Controller\Admin;

use App\Entity\Contest;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ContestCrudController extends AbstractCrudController
{
    public function __construct(
        #[Autowire('%image_dir%')] private readonly string $imageDir
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Contest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Конкурс')
            ->setEntityLabelInPlural('Конкурсы')
            ->setSearchFields(['name', 'slug', 'description'])
            ->setDefaultSort(['beginAt' => 'DESC'])
            ;
    }

    public function configureFields(
        string $pageName
    ): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnIndex()->onlyOnDetail();
        yield TextField::new('slug', 'Символьный код');

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

        yield DateTimeField::new('createdAt', 'Создание')->setFormTypeOptions([
            'years' => range(date('Y'), date('Y', strtotime('+1 year'))),
            'widget' => 'single_text',
        ]);

        //todo: сохранение в форме сохраняет не меняя имя файла
        yield ImageField::new('imagePath')
            ->setBasePath('/uploads/images')
            ->setUploadDir('/public/uploads/images')
            ->setLabel('Изображение');
    }
}
