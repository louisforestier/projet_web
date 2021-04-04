<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @return Response
     * @Route ("/ajoutProduits", name= "produit_ajout_produit")
     */
    public function ajoutProduitsAction(Request $request):Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || (!($utilisateur->getIsadmin())))
            throw new NotFoundHttpException("Vous n'avez pas les droits pour accéder à cette page.");
        else {
            $produit = new Produits();
            $form = $this->createForm(ProduitType::class,$produit);
            $form->add('send', SubmitType::class,['label'=>'ajouter produit']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $em->persist($produit);
                $em->flush();
                $this->addFlash('info','Le produit a été ajouter avec succes.');
                return $this->redirectToRoute('accueil');
            }

            $args = array('formAjoutProduit'=>$form->createView());
            return $this->render("Produit/ajoutProduit.html.twig",$args);
        }
    }
}
