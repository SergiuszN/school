<?php

namespace App\Service;

use App\Entity\EventRegistration;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TelegramNotify
{
    /** @var string */
    private $id;

    /** @var string */
    private $chat;

    public function __construct(ParameterBagInterface $bag)
    {
        $this->id = $bag->get('telegram_bot_id');
        $this->chat = $bag->get('telegram_bot_chat');
    }

    public function sendRegistrationNotify(EventRegistration $registration)
    {
        $message = "{$registration->getName()} <{$registration->getEmail()}> зарегестрировался на: {$registration->getEvent()->getName()}";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, "https://api.telegram.org/bot{$this->id}/sendMessage?chat_id={$this->chat}&text=$message");
        curl_exec($handle);
        curl_close($handle);
    }
}