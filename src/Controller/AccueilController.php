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
        return $this->render('accueil/accueilAdminVue.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @return Response
     * @Route ("/ajoutusers", name="ajout_users")
     */
    public function ajoutsUtilisateursAction():Response
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

    public function gestionUtilisateursAction():Response
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');
        $utilisateurs= $utilisateurRepository->findAll();
        $args = array("utilisateurs"=>$utilisateurs);
        return $this->render('Accueil/gestionUtilisateurs.html.twig');
    }


    /**
     * @Route ( "/testvue/1", name="accueil_testvue_1")
     */
    public function testvue1Action(): Response
    {
        return $this->render('accueil/accueilClienyVue.html.twig');
    }

}
