<?php
// src/Service/EmailService.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendPromotionEmail(array $users, string $promotionTitle, string $promotionDescription): void
    {
        foreach ($users as $user) {
            $email = (new Email())
                ->from('no-reply@votresalle.com')
                ->to($user->getEmail())
                ->subject('Nouvelle promotion disponible !')
                ->html($this->createEmailContent($user, $promotionTitle, $promotionDescription));

            $this->mailer->send($email);
        }
    }

    private function createEmailContent($user, $title, $description): string
    {
        return sprintf(
            '<h1>Bonjour %s,</h1>
            <p>Une nouvelle promotion est disponible dans votre salle de sport :</p>
            <h2>%s</h2>
            <p>%s</p>
            <p>Connectez-vous à votre compte pour en bénéficier !</p>',
            $user->getFirstName(),
            $title,
            $description
        );
    }
}