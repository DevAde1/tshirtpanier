<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description')->onlyOnForms(),
            ImageField::new('photo')->setBasePath('assets/uploads')->hideOnForm(),
            ImageField::new('photo')->setBasePath('assets/uploads')->setUploadDir('public/assets/uploads')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenCreating(),
            ImageField::new('photo')->setBasePath('assets/uploads')->setUploadDir('public/assets/uploads')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenUpdating()->setFormTypeOption('required', false),
            TextField::new('couleur'),
            ChoiceField::new('taille')->setChoices([
                'S' => 'petite',
                'M' => 'moyenne',
                'L' => 'grande',
            ]),
            ChoiceField::new('collection')->setChoices([
                'homme' => 'homme',
                'femme' => 'femme',
            ]),
            MoneyField::new('prix')->setCurrency('EUR'),
            IntegerField::new('stock', 'stock'),
            DateTimeField::new('dateEnregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
        ];
    }
    
    public function createEntity(string $entityFqcn)
    {
        $produit  = new $entityFqcn;
        $produit->setDateEnregistrement(new DateTime);
        return $produit;
    }
}
