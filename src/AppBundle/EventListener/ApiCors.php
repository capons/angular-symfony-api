<?php
/**
 * Joiz IP AG
 *
 * User: efun
 * Date: 07.07.14
 * Time: 13:48
 */

namespace  AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ApiCors
{
    public function onKernelRequest(GetResponseEvent $event)
    {

        $request = $event->getRequest();
        if ($request->headers->has("Access-Control-Request-Headers") && $request->headers->has("Access-Control-Request-Method")) {
            $response = new Response();
            //enable CORS - return the requested methods as allowed
            $response->headers->add(
                array('Access-Control-Allow-Headers' => $request->headers->get("Access-Control-Request-Headers"),
                    'Access-Control-Allow-Methods' => $request->headers->get("Access-Control-Request-Method"),
                    'Access-Control-Allow-Origin' => '*'));
            $event->setResponse($response);
            $event->stopPropagation();
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();
        if ($request->headers->has("Accept") && strstr($request->headers->get("Accept"),"application/json")) {
            $response->headers->add(array('Access-Control-Allow-Origin' => '*'));
        }
    }
}