<?php


namespace App\Services;

use App\Entity\Token;
use App\Entity\User;

class TokenSendler
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendToken(User $user, Token $token)
    {
        $message = (new \Swift_Message('Confirmez votre inscription'))
            ->setFrom('noreply@rent-car.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'emails/registration.html.twig',
                    ['token' => $token->getValue()]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
