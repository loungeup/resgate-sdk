<?php

namespace LU\Resgate\Message;

class Response
{
    private string $returnType;

    private string $queryString;

    private array $response;

    // TODO: PHP 8.1 use enum
    public const RES_NOTFOUND = "system.notFound"; // Return code 404
    public const RES_INVALIDPARAMS = "system.invalidParams"; // Return code 400
    public const RES_INVALIDQUERY = "system.invalidQuery"; // Return code 400
    public const RES_INTERNALERROR = "system.internalError"; // Return code 500
    public const RES_METHODNOTFOUND = "system.methodNotFound"; // Return code 400
    public const RES_ACCESSDENIED = "system.accessDenied"; // Return code 403
    public const RES_TIMEOUT = "system.timeout"; // Return code 408

    public function __construct($returnType = "json")
    {
        $this->returnType = $returnType;
    }

    public function getResponse(): string
    {
        if ($this->returnType === "json") {
            $return = json_encode($this->response);
        }

        return $return;
    }

    public function model($payload): string
    {
        $this->response = [
            "result" => [
                "model" => $payload,
            ],
        ];

        if ($this->queryString) {
            $this->response["result"]["query"] = $this->queryString;
        }

        return $this->getResponse();
    }

    public function result($payload): string
    {
        $this->response = [
            "result" => $payload,
        ];

        return $this->getResponse();
    }

    public function collection($payload): string
    {
        $this->response = [
            "result" => [
                "collection" => $payload,
            ],
        ];

        return $this->getResponse();
    }

    public function error(string $code, string $message, array $data = []): string
    {
        $this->response = [
            "error" => [
                "code" => $code,
                "message" => $message,
                "data" => $data,
            ],
        ];

        return $this->getResponse();
    }

    public function access(string $call): string
    {
        $this->response = [
            "result" => [
                "get" => true,
                "call" => $call,
            ],
        ];

        return $this->getResponse();
    }

    public function setQueryString(string $queryString)
    {
        $this->queryString = $queryString;
    }
}
