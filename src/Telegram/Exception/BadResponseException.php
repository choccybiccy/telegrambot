<?php

namespace Choccybiccy\Telegram\Exception;

use Choccybiccy\Telegram\Entity\Response;

class BadResponseException extends \Exception
{

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
