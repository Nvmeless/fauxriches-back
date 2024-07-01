<?php

namespace App\Controller\Admin;

use App\Entity\PoolCompletion;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class PoolCompletionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PoolCompletion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            AssociationField::new('player')->setSortProperty('ip'),
            AssociationField::new('song'),
            AssociationField::new('pool'),
            DateTimeField::new('createdAt'),
        ];
    }
}
