<?php

namespace LoungeUp\Resgate\Message;

enum ResponseError: string
{
    case RES_NOTFOUND = "system.notFound"; // Return code 404
    case RES_INVALIDPARAMS = "system.invalidParams"; // Return code 400
    case RES_INVALIDQUERY = "system.invalidQuery"; // Return code 400
    case RES_INTERNALERROR = "system.internalError"; // Return code 500
    case RES_METHODNOTFOUND = "system.methodNotFound"; // Return code 400
    case RES_ACCESSDENIED = "system.accessDenied"; // Return code 403
    case RES_TIMEOUT = "system.timeout"; // Return code 408
}

class Response
{
    private string $returnType;

    private string $queryString;

    private array $response;

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
        $resgatePayload = [];
        foreach ($payload as $key => $value) {
            if (is_array($value))
                $resgatePayload[$key]["data"] = $value;
            else
                $resgatePayload[$key] = $value;
        }

        $this->response = [
            "result" => [
                "model" => $resgatePayload,
            ],
        ];

        if ($this->queryString) {
            $this->response["result"]["query"] = $this->queryString;
        }

        return $this->getResponse();
    }

    public function resource($rid): string
    {
        $this->response = [
            "resource" => [
                "rid" => $rid,
            ],
        ];

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
