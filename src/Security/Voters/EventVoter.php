<?php

namespace App\Security\Voters;

use App\Entity\Event;
use DateTime;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EventVoter extends Voter
{
    const REGISTER = 'register';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::REGISTER])) {
            return false;
        }

        if (!$subject instanceof Event) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Event $subject */
        switch ($attribute) {
            case self::REGISTER:
                return $this->canRegister($subject);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canRegister(Event $subject)
    {
        $from = new DateTime();
        $to = (new DateTime())->modify(Event::AVAILABLE_FOR_REGISTRATION);
        $date = $subject->getDate();

        if ($date > $from && $date < $to) {
            return true;
        }

        return false;
    }
}