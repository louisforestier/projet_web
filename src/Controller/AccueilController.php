<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
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
        $admin = new Utilisateurs();
        $admin->setIdentifiant('admin')
            ->setMotdepasse('nimda')
            ->setIsadmin(true);
        $em->persist($admin);
        $gillou = new Utilisateurs();
        $gillou->setIdentifiant('gilles')
            ->setMotdepasse('sellig')
            ->setNom('Subrenat')
            ->setPrenom('Gilles')
            ->setAnniversaire(new \DateTime("01-01-2000"));
        $em->persist($gillou);
        $rita = new Utilisateurs();
        $rita->setIdentifiant('rita')
            ->setMotdepasse('atir')
            ->setNom('Zrour')
            ->setPrenom('Rita')
            ->setAnniversaire(new \DateTime("02-01-2000"));
        $em->persist($rita);

        $em->flush();
        return new Response('<body></body>');
    }

}
