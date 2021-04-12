<?php


namespace App\Service;


class Mailer
{
    public function envoiMessage($sujet,$texte, $expediteur,$destinataire,\Swift_Mailer $mailer){
        $message = (new \Swift_Message($sujet))
            ->setFrom($expediteur)
            ->setTo($destinataire)
            ->setBody($texte);
        $mailer->send($message);
    }
}