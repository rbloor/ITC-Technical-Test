<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use App\Factories\LoggerFactory;

abstract class Api
{
    protected $logger;
    protected $client;
    public const MAX_RETRIES = 3;

    public function __construct(?LoggerInterface $logger)
    {
        $this->logger = $logger ?? (new LoggerFactory())->create('getting-products-and-information');

        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'handler' => $this->createHandlerStack(),
            'timeout'  => 30.0,
            'headers' => [
                'Accept' => 'application/json',
            ],
         'verify' => false
        ]);
    }

    protected function createHandlerStack()
    {
        $stack = HandlerStack::create();
        $stack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
        return $this->createLoggingHandlerStack($stack);
    }

    protected function createLoggingHandlerStack(HandlerStack $stack)
    {
        $messageFormats = [
            '{method} {uri} HTTP/{version}',
            'HEADERS: {req_headers}',
            'BODY: {req_body}',
            'RESPONSE: {code} - {res_body}',
        ];

        foreach ($messageFormats as $messageFormat) {
            // We'll use unshift instead of push, to add the middleware to the bottom of the stack, not the top
            $stack->unshift(
                $this->createGuzzleLoggingMiddleware($messageFormat)
            );
        }

        return $stack;
    }

    protected function createGuzzleLoggingMiddleware(string $messageFormat)
    {
        return Middleware::log(
            $this->logger,
            new MessageFormatter($messageFormat)
        );
    }

    protected function retryDecider()
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            // Limit the number of retries to MAX_RETRIES
            if ($retries >= self::MAX_RETRIES) {
                return false;
            }

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                $this->logger->info('Timeout encountered, retrying');
                return true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= 500) {
                    $this->logger->info('Server 5xx error encountered, retrying...');
                    return true;
                }

                // Retry on api error
                $contents = json_decode($response->getBody()->getContents());
                if (property_exists($contents, 'error')) {
                    $this->logger->info('Data source error encountered, retrying...');
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * delay 1s 2s 3s 4s 5s ...
     *
     * @return callable
     */
    protected function retryDelay()
    {
        return function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        };
    }
}
