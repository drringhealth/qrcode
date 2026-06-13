<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $token = $request->getHeader('Authorization');
        if (!$token) {
            return response()
                ->setStatusCode(401)
                ->setJSON(['error' => 'Unauthorized - Missing token']);
        }

        $token = str_replace('Bearer ', '', $token->getValue());

        try {
            $key = config('App')->encryptionKey;
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
            $request->userId = $decoded->userId;
        } catch (\Exception $e) {
            return response()
                ->setStatusCode(401)
                ->setJSON(['error' => 'Unauthorized - Invalid token']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
