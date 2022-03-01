<?php

namespace LoungeUp\Resgate;

use LoungeUp\Nats\Connection;
use LoungeUp\Nats\Message;
use LoungeUp\NatsSdk\MessageDriver;
use LoungeUp\NatsSdk\Routing\Route;
use LoungeUp\Resgate\Controller\AbstractNatsController;
use LoungeUp\Resgate\Message\Request;

class ResgateMessageDriver implements MessageDriver
{
    public function __construct(public Connection $client)
    {
    }

    public function handle(Message $message, Route $route): string
    {
        // Create request
        $subject = $message->subject;
        $body = $message->data;
        $request = new Request($route, $subject, $body, $this->client);

        // Factory Controller
        $controller = $this->constructorFactory($route->getNamespace(), $route->getController(), $request);
        $call = $route->getMethod();

        // Call action / Get Response
        return $controller->$call();
    }

    private function constructorFactory($namespace, $controller, Request $request): AbstractNatsController
    {
        $controllerBuild = $namespace . "\\" . $controller;

        return new $controllerBuild($request);
    }
}
