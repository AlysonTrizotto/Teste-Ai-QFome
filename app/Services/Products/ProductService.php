<?php

namespace App\Services\Products;

use App\Helpers\CurlHelper;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    private $url;
    private $cache_time;
    private $cache_key;

    public function __construct(private CurlHelper $curl)
    {
        $this->url = config('services.fakestoreapi.url');
        $this->cache_time = config('services.fakestoreapi.ttl');
        $this->cache_key = config('services.fakestoreapi.cache');
    }

    /**
     * Busca todos os produtos com cache por 5 minutos
     *
     * @return array
     */
    public function index(): array
    {
        try {
            $response = Cache::remember($this->cache_key, $this->cache_time, function () {
                $response = $this->curl->get($this->url);
                $this->checkResponse($response, $this->cache_key);
                return $response;
            });
  
            return json_decode($response, true);
        } catch (\Exception $e) {
           throw new \Exception($e->getMessage());
        }
    }

    /**
     * Busca um produto por ID com cache por 5 minutos
     *
     * @param int $id
     * @return array|null
     */
    public function show(int $id): array|null
    {
        try {
            $response = Cache::remember($this->cache_key . '_' . $id, $this->cache_time, function () use ($id) {
                $response = $this->curl->get($this->url, $id);
                $this->checkResponse($response, $this->cache_key . '_' . $id);
                return $response;
            });

            return $response ? json_decode($response, true) : null;
        } catch (\Exception $e) {
           throw new \Exception($e->getMessage());
        }
    }

    public function showSmallFields(int $id): array|null
    {
        try {
            $response = Cache::remember($this->cache_key . '_' . $id, $this->cache_time, function () use ($id) {
                $response = $this->curl->get($this->url, $id);
                $this->checkResponse($response, $this->cache_key . '_' . $id);
                return $response;
            });

            $response = json_decode($response, true);
            return $response ? [
                'id' => $response['id'],
                'title' => $response['title'],
                'price' => $response['price'],
                'image' => $response['image'],
                'rating' => $response['rating'],
            ] : null;
        } catch (\Exception $e) {
           throw new \Exception($e->getMessage());
        }
    }

    /**
     * Verifica a resposta
     *
     * @param mixed $response
     * @param string $cacheKey
     * @return void
     */
    public function checkResponse(mixed $response, string $cacheKey): void
    {
        $message = 'Error fetching ' . $cacheKey;
        if ($response === false || $response === null) {
            $this->errorFetch($message);
        }

        if($this->curl->info()['http_code'] !== 200){
            Cache::forget($cacheKey);
            $this->errorFetch($message);
        }

        if($this->curl->error()) {
            Cache::forget($cacheKey);
            $this->errorFetch($message . ': ' . $this->curl->error());
        }
    }

    /**
     * Erro ao buscar produtos ou produto
     *
     * @param string $message
     * @return void
     */
    public function errorFetch(string $message): void
    {
        throw new \Exception($message);
    }

}

