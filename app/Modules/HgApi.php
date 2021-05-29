<?php

namespace DollarQuotation;

class HgApi
{
    private $key = null;
    private $error = false;

    public function __construct(string $key = null)
    {
        if (!empty($key)) {
            $this->key = $key;
        }
    }

    public function request(string $endpoint = '', array $params = [])
    {
        $uri = "https://api.hgbrasil.com/" . $endpoint . "?key=" . $this->key . "&format=json";

        if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (empty($value)) {
                    continue;
                }

                $uri .= $key . "=" . urlencode($value) . '&';
            }
            $uri .= substr($uri, 0, -1);
            $response = @file_get_contents($uri);

            $this->errror = false;

            return json_decode($response, true);
        } else {
            $this->error = true;
            return false;
        }
    }

    public function isError()
    {
        return $this->error;
    }

    public function dollarQuotation()
    {
        $data = $this->request('finance/quotations');

        if (!empty($data) && is_array($data['results']['currencies']['USD'])) {
            $this->error = false;

            return $data['results']['currencies']['USD'];
        } else {
            $this->error = true;

            return false;
        }
    }
}
