<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @return Response
     * @Route ("/gerer_panier", name="panier_gerer_panier")
     */
    public function gererPanier() : Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || ($utilisateur->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits.");
        else {
            $panierRepository = $em->getRepository('App:Panier');
            $panierClient = $panierRepository->getPanierUtil($utilisateur);
        }
        $args = array("panier"=>$panierClient);
        return $this->render("Panier/panier.html.twig",$args);
    }

    /**
     * @return Response
     * @Route ("/panier/commander", name="panier_commander")
     */
    public function commander() : Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || ($utilisateur->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits.");
        else {
            $panierRepository = $em->getRepository('App:Panier');
            $panierClient = $panierRepository->getPanierUtil($utilisateur);
            foreach ($panierClient as $item){
                $ligne = $panierRepository->find($item['id']);
                $em->remove($ligne);
            }
            $em->flush();
            return $this->redirectToRoute("panier_gerer_panier");
        }
    }

    /**
     * @Route ("/panier/vider", name="panier_vider")
     */
    public function vider() : Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || ($utilisateur->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits.");
        else {
            $panierRepository = $em->getRepository('App:Panier');
            $panierClient = $panierRepository->getPanierUtil($utilisateur);
            foreach ($panierClient as $item){
                $ligne = $panierRepository->find($item['id']);
                $produit = $ligne->getProduit();
                $produit->setQuantite($produit->getQuantite()+$ligne->getQuantite());
                $em->remove($ligne);
            }
            $em->flush();
            return $this->redirectToRoute("panier_gerer_panier");
        }
    }

    /**
     * @Route ("/panier/supp/elem/{id}", name="panier_supp_panier")
     */
    public function supprimer($id) : Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || ($utilisateur->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits.");
        else {
            $panierRepository = $em->getRepository('App:Panier');
            /** @var Panier $panier */
            $panier = $panierRepository->find($id);
            $produit = $panier->getProduit();
            $produit->setQuantite($produit->getQuantite() + $panier->getQuantite());
            $em->remove($panier);
            $em->flush();
            return $this->redirectToRoute("panier_gerer_panier");
        }
    }
}
