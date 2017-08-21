<?php
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class ApiExceptionJson implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        if (!$e instanceof ApiProblemException) {
            return;
        }

        $apiProblem = $e->getApiProblem();
        $response = new JsonResponse(
            $apiProblem->toArray(),
            $apiProblem->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);
    }
    public static function getSubscribedEvents()
    {
        /*
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
        */
        return array(
            KernelEvents::REQUEST  => array('onKernelRequest', 9999),
            KernelEvents::RESPONSE => array('onKernelResponse', 9999),
            //KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
    public function getApiProblem()
    {
        return $this->apiProblem;
    }
    
    public function onKernelRequest(GetResponseEvent $event) {
        // Don't do anything if it's not the master request.

        if (!$event->isMasterRequest()) {
            return;
        }
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        // Don't do anything if it's not the master request.

        if (!$event->isMasterRequest()) {
            return;
        }
    }

}