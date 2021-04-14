<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(): Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        return $this->render('Accueil/accueil.html.twig', ['utilisateur' => $utilisateur]);
    }

    public function imageEntete():Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        return $this->render("Layouts/imageEntete.html.twig", ['utilisateur'=>$utilisateur]);
    }

    public function menu():Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        $produitRepository = $em->getRepository('App:Produit');
        $query = $produitRepository->createQueryBuilder('p')
            ->select('sum(p.quantite)');
        $nb = $query->getQuery()->getSingleScalarResult();
        return $this->render("Layouts/menu.html.twig", ['utilisateur'=>$utilisateur,'produits'=>$nb]);
    }








   

}
