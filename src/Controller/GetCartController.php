<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\View\CartView;

final class GetCartController
{
    public function __construct(
        private CartView    $cartView,
        private CartManager $cartManager
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        try {
            $cart = $this->cartManager->getCart();

            if (!$cart) {
                $response->getBody()->write(json_encode([
                    'status'  => 'error',
                    'message' => 'Cart not found',
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                return $response
                    ->withHeader('Content-Type', 'application/json; charset=utf-8')
                    ->withStatus(404);
            }

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'cart'   => $this->cartView->toArray($cart),
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
                ->withStatus(500);
        }
    }
}