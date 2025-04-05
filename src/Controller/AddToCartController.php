<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Service\AddItemProduct;
use Raketa\BackendTestTask\View\CartView;

final class AddToCartController
{
    public function __construct(
        private AddItemProduct $cartService,
        private CartView       $cartView
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);

            if (!isset($rawRequest['productUuid'], $rawRequest['quantity'])) {
                throw new \InvalidArgumentException('Missing productUuid or quantity');
            }

            $cart = $this->cartService->addProductToCart(
                $rawRequest['productUuid'],
                (int)$rawRequest['quantity']
            );

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'cart'   => $this->cartView->toArray($cart)
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
