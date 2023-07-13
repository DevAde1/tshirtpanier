<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [        
        IdField::new('id')->hideOnForm(),
        IntegerField::new('quantite'),
        MoneyField::new('montant')->setCurrency('EUR')->hideOnForm(),
        ChoiceField::new('etat')->setChoices([
            'En cours de traitement' => 'En cours de traitement',
            'Envoyé' => 'Envoyé',
            'Livré' => 'Livré',
        ]),
        DateTimeField::new('dateEnregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
        AssociationField::new('produit'),
        AssociationField::new('membre'),



    ];
    }
    public function createEntity(string $entityFqcn)
    {
        $commande = new $entityFqcn;
        
        $commande->setDateEnregistrement(new \Datetime);

        return $commande;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $prix = $entityInstance->getProduit()->getPrix();
        $quantite = $entityInstance->getQuantite();
        $entityInstance->setMontant($prix*$quantite);

        $entityManager->persist($entityInstance);

        $entityManager->flush();
    }
}
