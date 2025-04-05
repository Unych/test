<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\View\ProductsView;

final class GetProductsController
{
    public function __construct(
        private ProductsView $productsView
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);

            if (!isset($rawRequest['category'])) {
                throw new \InvalidArgumentException('Missing category');
            }

            $products = $this->productsView->toArray($rawRequest['category']);

            $response->getBody()->write(json_encode([
                'status'   => 'success',
                'products' => $products
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(200);

        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(400);
        }
    }
}