<?php

namespace App\Helpers;

class CurlHelper
{
    private $curl;

    /**
     * CurlHelper constructor.
     * Initialize cURL and set default options.
     */
    public function __construct()
    {
        $this->curl = curl_init();
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->setOpt(CURLOPT_ENCODING, '');
        $this->setOpt(CURLOPT_MAXREDIRS, 10);
        $this->setOpt(CURLOPT_TIMEOUT, 0);
        $this->setOpt(CURLOPT_FOLLOWLOCATION, true);
        $this->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $this->setOpt(CURLOPT_HTTPHEADER, array('Accept: application/json'));
    }

    /**
     * Set cURL options.
     *
     * @param int $opt
     * @param mixed $value
     */
    public function setOpt($opt, $value)
    {
        curl_setopt($this->curl, $opt, $value);
    }

    /**
     * Execute cURL session.
     *
     * @return string
     */
    public function exec()
    {
        return curl_exec($this->curl);
    }

    /**
     * Make a GET request.
     *
     * @param string $url
     * @param int|null $id
     * @return string
     */
    public function get($url, $id = null)
    {
        $this->setOpt(CURLOPT_URL, $id !== null ? rtrim($url, '/') . '/' . $id : $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');

        return $this->exec();
    }

    /**
     * Get cURL information.
     *
     * @return array
     */
    public function info()
    {
        return curl_getinfo($this->curl);
    }

    /**
     * Get cURL error.
     *
     * @return string
     */
    public function error()
    {
        return curl_error($this->curl);
    }

    public function __destruct()
    {
        if ($this->curl) {
            curl_close($this->curl);
        }
    }
}