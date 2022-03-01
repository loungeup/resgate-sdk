<?php

namespace LoungeUp\Resgate\Controller;

use LoungeUp\Resgate\Message\Request;
use LoungeUp\Resgate\Message\Response;

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
