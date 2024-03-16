<?php

namespace Frame\Http;

class JsonResponse extends Response
{
    /**
     * Create a new JSON response.
     * 
     * @param mixed $data
     * @param int   $statusCode
     * @param array $headers
     * 
     * @return void
     */
    public function __construct(mixed $data = '', int $statusCode = 200, array $headers = [])
    {
        parent::__construct($data, $statusCode, $headers);

        $this->data = json_encode($data);

        $this->headers['Content-Type'] = 'application/json';
    }
}
