<?php
namespace LU\Resgate\Message;

use LU\Nats\Routing\Route;
use Nats\Connection;

class Request
{
    // Event from NATS subscriber, e.g. event.model.*
    private string $event;

    // Event from Router with variables, e.g. event.model.{id}
    private string $originalEvent;

    // Catched event, e.g. event.model.123
    private string $receivedEvent;

    private string $messageBody;

    private array $eventParams = [];

    private array $query = [];

    private string $queryString = "";

    private array $body;

    private Connection $client;

    public function __construct(Route $route, string $receivedEvent, string $messageBody)
    {
        $this->event = $route->getEvent();
        $this->originalEvent = $route->getEventRoute();
        $this->receivedEvent = $receivedEvent;
        $this->messageBody = $messageBody;
        $this->parseSubject();
        $this->parseMessageBody();
    }

    private function parseSubject()
    {
        $arrayReceivedEvent = explode(".", $this->receivedEvent);
        $output = [];

        // Extract {item} to item => value
        preg_match_all("/\.[\w]*({.*?})?/", $this->originalEvent, $matches);
        foreach ($matches[1] as $key => $values) {
            $name = substr($values, 1, -1);
            if (!empty($name)) {
                $output[$name] = $arrayReceivedEvent[$key + 1];
            }
        }

        $this->eventParams = $output;
    }

    private function parseMessageBody()
    {
        if (isset($this->messageBody) && !empty($this->messageBody)) {
            // TODO: extract all except filter, ..... ?
            $this->body = json_decode($this->messageBody, true);
        }

        $this->parseFilters();
    }

    private function parseFilters()
    {
        if (isset($this->body) && isset($this->body["query"])) {
            $this->queryString = $this->body["query"];
            parse_str($this->body["query"], $this->query);
        }
    }

    public function systemReset(Connection $natsConnection, array $resources, string $inbox = null)
    {
        $natsConnection->publish("system.reset", json_encode(["resources" => $resources]), $inbox);
    }

    public function get(string $name): ?string
    {
        return $this->eventParams[$name] ?? null;
    }

    public function all(): array
    {
        return $this->eventParams;
    }

    public function getQuery(string $name): ?string
    {
        return $this->query[$name] ?? null;
    }

    public function getQueries(): array
    {
        return $this->query;
    }

    public function getMessageBody(): string
    {
        return $this->messageBody;
    }

    public function setMessageBody(string $messageBody)
    {
        $this->messageBody = $messageBody;
    }

    public function getQueryString(): string
    {
        return $this->queryString;
    }

    public function setQueryString(string $queryString)
    {
        $this->queryString = $queryString;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getNatsEvent(): string
    {
        return $this->event;
    }

    public function setNatsEvent(string $event)
    {
        $this->event = $event;
    }

    public function getOriginalEvent(): string
    {
        return $this->originalEvent;
    }

    public function setOriginalEvent(string $originalEvent)
    {
        $this->originalEvent = $originalEvent;
    }

    public function getReceivedEvent(): string
    {
        return $this->receivedEvent;
    }

    public function setReceivedEvent(string $receivedEvent)
    {
        $this->receivedEvent = $receivedEvent;
    }

    public function getEventParams(): array
    {
        return $this->eventParams;
    }

    public function setEventParams(array $eventParams)
    {
        $this->eventParams = $eventParams;
    }

    public function getClient(): Connection
    {
        return $this->client;
    }
}
