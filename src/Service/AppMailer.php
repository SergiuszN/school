<?php

namespace App\Service;

use App\DTO\ContactDTO;
use App\Entity\EventRegistration;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AppMailer
{
    private Environment $twig;
    private MailerInterface $mailer;
    private string $fromEmail;

    public function __construct(ParameterBagInterface $bag, MailerInterface $mailer, Environment $twig)
    {
        $this->fromEmail = $bag->get('app_email');
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    private function send($to, $subject, $template, $params = [], $attachments = []): void
    {
        $email = (new Email())
            ->from(Address::create("ACNN_SCHOOL <{$this->fromEmail}>"))
            ->to($to)
            ->subject($subject)
            ->html($this->twig->render($template, $params));

        foreach ($attachments as $attachment) {
            $email->attachFromPath($attachment[0], $attachment[1]);
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->mailer->send($email);
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendContact(ContactDTO $contactDTO): void
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

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendEventConfirmRegistration(EventRegistration $eventRegistration): void
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

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendEventSuccessRegistration(EventRegistration $eventRegistration): void
    {
        $this->send(
            $eventRegistration->getEmail(),
            "Ви успішно зареєструвалися",
            'emails/event_success_registration.html.twig',
            [
                'eventRegistration' => $eventRegistration,
            ],
            [
                [__DIR__ . '/../../public' . $eventRegistration->getEvent()->getProgram(), 'Програма курсу.pdf'],
                [__DIR__ . '/../../public' . $eventRegistration->getEvent()->getInvoice(), 'Рахунок.pdf']
            ]
        );
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendEventPayedRegistration(EventRegistration $eventRegistration): void
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