<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\Mailer;
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
    public function ajoutProduitsAction(Request $request): Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || (!($utilisateur->getIsadmin())))
            throw new NotFoundHttpException("Vous n'avez pas les droits pour accéder à cette page.");
        else {
            $produit = new Produit();
            $form = $this->createForm(ProduitType::class, $produit);
            $form->add('send', SubmitType::class, ['label' => 'ajouter produit']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($produit);
                $em->flush();
                $this->addFlash('info', 'Le produit a été ajouter avec succes.');
                return $this->redirectToRoute('accueil');
            }

            $args = array('formAjoutProduit' => $form->createView());
            return $this->render("Produit/ajoutProduit.html.twig", $args);
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/voirMagasin", name="produit_magasin")
     */
    public function voirMagasinAction(Request $request): Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || ($utilisateur->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits pour accéder à cette page.");
        else {
            $form = $request->request;

            if (is_null($form)) {
            } else {
                foreach ($form as $k => $v) {
                    dump($k);
                    dump($v);
                    //faire ope sur le panier
                }
            }

            $produitRepository = $em->getRepository('App:Produit');
            $produits = $produitRepository->findAll();
            $args = array('produits' => $produits);
            return $this->render("Produit/magasin.html.twig", $args);
        }
    }

    /**
     * @Route ("/traitMagasin", name="produit_trait_magasin")
     */
    public function traitMagasinAction(Request $request): Response
    {

    }

    /**
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route(
     *     "/envoimail",
     *     name="produit_envoi_mail"
     * )
     */
    public function envoiNbProduit(Mailer $mailer,\Swift_Mailer $swift_Mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $produitRepository = $em->getRepository(Produit::class);
        $query = $produitRepository->createQueryBuilder('p')
            ->select('sum(p.quantite)');
        $nb = $query->getQuery()->getSingleScalarResult();
        $sujet = 'Nombre de produits';
        $texte = 'Nous avons ' . $nb .' produits en stocks.';
        $expediteur = 'adresseexpediteur@à.remplir';
        $destinataire = 'adressedestinataire@à.remplir';
        $mailer->envoiMessage($sujet,$texte,$expediteur,$destinataire,$swift_Mailer);
        return $this->redirectToRoute('produit_magasin');
    }

}
