<?php

namespace LU\Resgate;

use Nats\Message;
use LU\Resgate\Controller\AbstractNatsController;
use LU\Nats\MessageDriver;
use LU\Nats\Routing\Route;
use LU\Resgate\Message\Request;

class ResgateMessageDriver implements MessageDriver
{
    public function handle(Message $message, Route $route): string
    {
        // Create request
        $subject = $message->getSubject();
        $body = $message->getBody();
        $request = new Request($route, $subject, $body);

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
