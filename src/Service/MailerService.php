<?php

namespace App\Service;

use App\Entity\Affiliate;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

class MailerService
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var EngineInterface */
    private $templateEngine;

    /**
     * @param Swift_Mailer $mailer
     * @param EngineInterface $templateEngine
     */
    public function __construct(Swift_Mailer $mailer, EngineInterface $templateEngine)
    {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
    }

    /**
     * @param Affiliate $affiliate
     *
     * @return int
     */
    public function sendActivationEmail(Affiliate $affiliate): int
    {
        $message = (new Swift_Message())
            ->setSubject('Account activation')
            ->setFrom('jobeet@example.com')
            ->setTo($affiliate->getEmail())
            ->setBody(
                $this->templateEngine->render(
                    'emails/affiliate_activation.html.twig',
                    [
                        'token' => $affiliate->getToken(),
                    ]
                ),
                'text/html'
            );

        return $this->mailer->send($message);
    }
}