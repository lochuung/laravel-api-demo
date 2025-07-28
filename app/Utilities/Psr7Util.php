<?php

namespace App\Utilities;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class Psr7Util
{
    public static function createPsr7Request($request): ServerRequestInterface
    {
        if ($request instanceof ServerRequestInterface) {
            return $request;
        }

        // Convert Laravel request to PSR-7 request
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        return $psrHttpFactory->createRequest($request);
    }

    public static function createPsr7Response($response = null): ResponseInterface
    {
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        // Convert Laravel response to PSR-7 response
        $psr17Factory = new Psr17Factory();
        if (!isset($response)) {
            return $psr17Factory->createResponse();
        }
        return $psr17Factory->createResponse($response->getStatusCode())
            ->withBody($psr17Factory->createStream($response->getContent()));
    }
}
