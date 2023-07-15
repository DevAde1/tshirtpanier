<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Service\CartService;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cs): Response
    {
        // $session = $rs->getSession();
        // $cart = $session->get('cart', []);
        
        // //* je vais créer un nouveau tableau qui contiendra des objets Product et les quantité de chaque objet
        // $cartWithData = [];
        // $total = 0;
        // //*Pour chaque $id qui se trouve dans mon tableau $cart, j'ajoute une case au tableau $cartWithData, qui est multidimensionnel
        // //* chaque case est elle-même un tableau associatif contenant 2 cases : une case 'product' (produit entier récupéré en BDD) et une case 'quantity' (avec la quantité de se produit présent dans le panier)
        // foreach($cart as $id => $quantity)
        // {
        //     $produit = $repo->find($id);
        //     $cartWithData[] = [
        //         'product' => $produit,
        //         'quantity' => $quantity
        //     ];
        //     $total += $produit->getPrice() * $quantity;
        // }
        $cartWithData = $cs->cartWithData();
        $total = $cs->total();
        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, CartService $cs)
    {
        $cs->add($id);
        // dd($session->get('cart'));
        $this->addFlash('success', 'ajout du produit dans le panier');
        return $this->redirectToRoute('home');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('app_cart');
    }


    #[Route('/cart/achat', name: 'achat_tshirt')]
public function achatTshirt(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    $cartItems = $session->get('cart', []);

    // Création de la commande
    $commande = new Commande();
    // Récupération de l'utilisateur courant
    $user = $this->getUser();
    $commande->setMembre($user);
    $commande->setDateEnregistrement(new \DateTime());

    $montantTotal = 0;

    foreach ($cartItems as $id => $quantity) {
        // Ajout des produits à la commande avec la quantité appropriée
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $commande->setProduit($produit)->setQuantite($quantity);

        // Calcul du montant total en ajoutant le prix du produit multiplié par la quantité
        $montantTotal += $produit->getPrix() * $quantity;
    }

    $commande->setMontant($montantTotal);
    $commande->setEtat('En cours de traitement'); // Définition de l'état de la commande

    // Persist et flush de la commande dans la base de données
    $entityManager->persist($commande);
    $entityManager->flush();

    // Suppression du panier de la session
    $session->remove('cart');

    $this->addFlash('success', 'Votre commande a bien été enregistrée !');


    // Redirection vers la page de profil
    return $this->redirectToRoute('profil');
}

    
    
    
     #[Route("/cart/profil", name:"profil")]
    public function profil(CommandeRepository $repo)
    {
        $commandes = $repo->findBy(['membre' => $this->getUser()]);

        return $this->render("cart/profil.html.twig", [
            'commandes' => $commandes
        ]);
    }

    
}