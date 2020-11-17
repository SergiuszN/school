<?php

namespace App\Service;

use App\Entity\EventRegistration;
use App\Entity\Testimonial;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class TelegramNotify
{
    /** @var string */
    private $id;

    /** @var string */
    private $chat;

    /** @var RouterInterface */
    private $router;

    public function __construct(ParameterBagInterface $bag, RouterInterface $router)
    {
        $this->id = $bag->get('telegram_bot_id');
        $this->chat = $bag->get('telegram_bot_chat');
        $this->router = $router;
    }

    public function sendRegistrationNotify(EventRegistration $registration)
    {
        $message = "{$registration->getName()} <{$registration->getEmail()}> зарегестрировался на: {$registration->getEvent()->getName()}";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, "https://api.telegram.org/bot{$this->id}/sendMessage?chat_id={$this->chat}&text=$message");
        curl_exec($handle);
        curl_close($handle);
    }

    public function sendTestimonialNotify(Testimonial $testimonial)
    {
        $url = $this->router->generate(
            'admin_testimonial_public',
            ['testimonial' => $testimonial->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $message = "Новый отзыв от: {$testimonial->getName()} ({$testimonial->getRating()}) \n";
        $message .= "-------------------------------------------------------- \n\n";
        $message .= "{$testimonial->getContent()} \n\n";
        $message .= "-------------------------------------------------------- \n\n";
        $message .= "[Опубликовать]({$url})";
        $message = urlencode($message);

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, "https://api.telegram.org/bot{$this->id}/sendMessage?chat_id={$this->chat}&parse_mode=markdown&text=$message");
        curl_exec($handle);
        curl_close($handle);

        die();
    }
}