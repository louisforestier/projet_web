<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UtilisateurController
 * @package App\Controller
 * @Route ("/utilisateur")
 */
class UtilisateurController extends AbstractController
{

    /**
     * @return Response
     * @Route(
     *     "/connexion",
     *     name="utilisateur_connexion"
     * )
     */
    public function connexionAction():Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur_connecte = $utilisateurRepository->find($user);
        if (!is_null($utilisateur_connecte))
            throw new NotFoundHttpException("Vous êtes déjà connecté.");
        else {
            return $this->render("Utilisateur/connexion.html.twig");
        }
    }

    /**
     * @return Response
     * @Route ("/ajoutusers", name="ajout_users")
     */
    public function ajoutsUtilisateursEnDurAction():Response
    {
        $em = $this->getDoctrine()->getManager();
        $admin = new Utilisateur();
        $admin->setIdentifiant('admin')
            ->setMotdepasse('nimda')
            ->setIsadmin(true);
        $em->persist($admin);
        $gillou = new Utilisateur();
        $gillou->setIdentifiant('gilles')
            ->setMotdepasse('sellig')
            ->setNom('Subrenat')
            ->setPrenom('Gilles')
            ->setAnniversaire(new \DateTime("01-01-2000"));
        $em->persist($gillou);
        $rita = new Utilisateur();
        $rita->setIdentifiant('rita')
            ->setMotdepasse('atir')
            ->setNom('Zrour')
            ->setPrenom('Rita')
            ->setAnniversaire(new \DateTime("02-01-2000"));
        $em->persist($rita);

        $em->flush();
        return new Response('<body></body>');
    }


    /**
     * @param Request $request
     * @return Response
     * @Route (
     *     "/creer",
     *     name="utilisateur_creer"
     * )
     */
    public function creerClientAction(Request $request):Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur_connecte = $utilisateurRepository->find($user);
        if (!is_null($utilisateur_connecte))
            throw new NotFoundHttpException("Vous êtes déjà connecté.");
        else {
            $utilisateur = new Utilisateur();
            $form = $this->createForm(UtilisateurType::class,$utilisateur);
            $form->add('send', SubmitType::class,['label'=>'Créer compte']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $em->persist($utilisateur);
                $em->flush();
                $this->addFlash('info','Votre compte a été créé avec succès.');
                return $this->redirectToRoute('accueil');
            }

            $args = array('formCreerClient'=>$form->createView());
            return $this->render("Utilisateur/creerClient.html.twig",$args);
        }

    }

    /**
     * @return Response
     * @Route (
     *     "/deconnexion",
     *     name="utilisateur_deconnexion"
     * )
     */
    public function deconnexionAction():Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur_connecte = $utilisateurRepository->find($user);
        if (is_null($utilisateur_connecte))
            throw new NotFoundHttpException("Vous êtes déjà connecté.");
        else {
            $this->addFlash("info","Vous auriez dû être déconnecté.");
            return $this->redirectToRoute("accueil");
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route (
     *     "/modifier_profil",
     *     name="utilisateur_modifier_profil",
     * )
     */
    public function modifierProfilAction(Request $request):Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur = $utilisateurRepository->find($user);
        if (is_null($utilisateur) || ($utilisateur->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits pour accéder à cette page.");
        else {
            $form = $this->createForm(UtilisateurType::class,$utilisateur);
            $form->add('send',SubmitType::class,['label'=>'Modifier profil']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $em->flush();
                $this->addFlash('info','Votre profil a été modifié avec succès.');
                $this->redirectToRoute('produit_magasin');
            }
            $args = array('formModifierProfil'=>$form->createView());
            return $this->render('Utilisateur/modifierProfil.html.twig',$args);
        }
    }

    /**
     * @return Response
     * @Route (
     *     "/gestion",
     *     name = "utilisateur_gestion"
     * )
     */
    public function gestionUtilisateursAction(): Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur_connecte = $utilisateurRepository->find($user);
        if (is_null($utilisateur_connecte) || (!$utilisateur_connecte->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits pour accéder à cette page.");
        else {
            $utilisateurs = $utilisateurRepository->findAll();
            $args = array("utilisateurs" => $utilisateurs, "user" => $user);
            return $this->render('Utilisateur/gestionUtilisateurs.html.twig',$args);
        }
    }

    /**
     * @param $id
     * @return Response
     * @Route (
     *     "/supprimer/{id}",
     *     name = "utilisateur_supprimer",
     *     requirements={"id"="[1-9]\d*"}
     * )
     */
    public function supprimerUtilisateurAction($id):Response
    {
        $user = $this->getParameter('user');
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateur_connecte = $utilisateurRepository->find($user);
        $utilisateur = $utilisateurRepository->find($id);
        if (is_null($utilisateur_connecte) || (!$utilisateur_connecte->getIsadmin()))
            throw new NotFoundHttpException("Vous n'avez pas les droits pour accéder à cette page.");
        elseif (is_null($utilisateur))
            throw new NotFoundHttpException("Cet utilisateur n'existe pas.");
        else {
            $em->remove($utilisateur);
            $em->flush();
            return $this->redirectToRoute("utilisateur_gestion");

        }
    }
}

//Clementine Guillot et Louis Forestier