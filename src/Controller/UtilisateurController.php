<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     * @Route (
     *     "/supprimer/{id}",
     *     name = "utilisateur_supprimer"
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
            throw new NotFoundHttpException("Cet identifiant d'utilisateurs n'existe pas.");
        else {
            $em->remove($utilisateur);
            $em->flush();
            return $this->redirectToRoute("utilisateur_gestion");

        }
    }

}
