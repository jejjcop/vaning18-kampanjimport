<?php

class HttpClient 
{
    private int $timeout;

    public function __construct(int $timeout = 10) 
    {
        $this->timeout = $timeout;
    }

    /**
     * Make a GET-request with CURL
     */
    public function get(string $url)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $headers = [];

        if (!empty($headers)) 
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) 
        {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            throw new Exception("CURL-error: " . $errorMessage);
        }

        return $response;
    }
}
