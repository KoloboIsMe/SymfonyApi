<?php

namespace App\Event\Subscriber;

use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorizationCodeSubscriber implements EventSubscriberInterface
{
    public function onLeagueOauth2ServerEventAuthorizationRequestResolve(AuthorizationRequestResolveEvent $event): void
    {
        $event->resolveAuthorization(true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OAuth2Events::AUTHORIZATION_REQUEST_RESOLVE => 'onLeagueOauth2ServerEventAuthorizationRequestResolve',
        ];
    }
}