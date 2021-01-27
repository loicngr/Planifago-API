<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    /** @var RequestStack */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        $payload = $event->getData();
        $user = $event->getUser();

        if(!$user instanceof UserInterface) {
            return;
        }

        $payload['ip'] = $request->getClientIp();
        $payload['id'] = '/api/users/' . $user->getId();
        $event->setData($payload);

        $header        = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }

}