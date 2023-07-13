<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $this->addFlash('success', 'ajout du produit dans la panier');
        return $this->redirectToRoute('home');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('app_cart');
    }
}
