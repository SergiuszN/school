<?php

namespace App\Service;

use App\DTO\ContactDTO;
use App\Entity\EventRegistration;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class AppMailer
{
    /** @var Environment */
    private $twig;

    /** @var MailerInterface */
    private $mailer;

    /** @var string */
    private $fromEmail;

    public function __construct(ParameterBagInterface $bag, MailerInterface $mailer, Environment $twig)
    {
        $this->fromEmail = $bag->get('app_email');
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    private function send($to, $subject, $template, $params = [], $attachments = [])
    {
        $email = (new Email())
            ->from(Address::fromString("ACNN_SCHOOL <{$this->fromEmail}>"))
            ->to($to)
            ->subject($subject)
            ->html($this->twig->render($template, $params));

        foreach ($attachments as $attachment) {
            $email->attachFromPath($attachment[0], $attachment[1]);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->mailer->send($email);
    }

    public function sendContact(ContactDTO $contactDTO)
    {
        $this->send(
            $this->fromEmail,
            "Питання від: {$contactDTO->getEmail()}",
            'emails/contact.html.twig',
            [
                'name' => $contactDTO->getName(),
                'email' => $contactDTO->getEmail(),
                'question' => $contactDTO->getQuestion(),
            ]
        );
    }

    public function sendEventConfirmRegistration(EventRegistration $eventRegistration)
    {
        $this->send(
            $eventRegistration->getEmail(),
            "Будь ласка підтвердіть свою реєстрацію",
            'emails/event_confirm_registration.html.twig',
            [
                'eventRegistration' => $eventRegistration,
            ]
        );
    }

    public function sendEventSuccessRegistration(EventRegistration $eventRegistration)
    {
        $this->send(
            $eventRegistration->getEmail(),
            "Ви успішно зареєструвалися",
            'emails/event_success_registration.html.twig',
            [
                'eventRegistration' => $eventRegistration,
            ],
            [
                [__DIR__ . '/../../public' . $eventRegistration->getEvent()->getProgram(), 'Програма курсу'],
                [__DIR__ . '/../../public' . $eventRegistration->getEvent()->getInvoice(), 'Рахунок']
            ]
        );
    }

    public function sendEventPayedRegistration(EventRegistration $eventRegistration)
    {
        $this->send(
            $eventRegistration->getEmail(),
            "Оплату отримано.",
            'emails/event_payed.html.twig',
            [
                'eventRegistration' => $eventRegistration,
            ]
        );
    }
}