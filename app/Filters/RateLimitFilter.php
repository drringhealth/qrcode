<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RateLimitFilter implements FilterInterface
{
    protected $limit = 60; // requests per minute
    protected $cache;

    public function __construct()
    {
        $this->cache = service('cache');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $ip = $request->getIPAddress();
        $key = 'rate_limit_' . $ip;

        $count = $this->cache->get($key) ?? 0;

        if ($count >= $this->limit) {
            return response()
                ->setStatusCode(429)
                ->setJSON(['error' => 'Too many requests']);
        }

        $this->cache->save($key, $count + 1, 60); // 1 minute expiry
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
