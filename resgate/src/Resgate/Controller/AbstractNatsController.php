<?php

namespace LU\Resgate\Controller;

use LU\Resgate\Message\Request;
use LU\Resgate\Message\Response;

class AbstractNatsController
{
    protected Request $request;

    protected Response $response;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = new Response();
        $this->response->setQueryString($this->request->getQueryString());
    }
}
