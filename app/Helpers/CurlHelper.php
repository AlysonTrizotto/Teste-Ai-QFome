<?php

namespace App\Helpers;

class CurlHelper
{
    private $curl;

    /**
     * Inicializa o cURL e define opções padrão.
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
     * Define opções do cURL.
     *
     * @param int $opt
     * @param mixed $value
     */
    public function setOpt($opt, $value)
    {
        curl_setopt($this->curl, $opt, $value);
    }

    /**
     * Executa a sessão cURL.
     *
     * @return string
     */
    public function exec()
    {
        return curl_exec($this->curl);
    }

    /**
     * Realiza uma requisição GET.
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
     * Obtém informações do cURL.
     *
     * @return array
     */
    public function info()
    {
        return curl_getinfo($this->curl);
    }

    /**
     * Obtém o erro do cURL.
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