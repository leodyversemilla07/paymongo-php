<?php

declare(strict_types=1);

namespace Paymongo;

use Paymongo\Services\ServiceFactory;
use Paymongo\Services\BaseService;

/**
 * Main client for interacting with the PayMongo API.
 *
 * @package Paymongo
 * 
 * @property-read Services\LinkService $links
 * @property-read Services\CustomerService $customers
 * @property-read Services\PaymentService $payments
 * @property-read Services\PaymentIntentService $paymentIntents
 * @property-read Services\PaymentMethodService $paymentMethods
 * @property-read Services\RefundService $refunds
 * @property-read Services\SourceService $sources
 * @property-read Services\WebhookService $webhooks
 * @property-read Services\CheckoutSessionService $checkoutSessions
 * @property-read Services\CustomerV2Service $customersV2
 * @property-read Services\SubscriptionService $subscriptions
 * @property-read Services\PlanService $plans
 * @property-read Services\InvoiceService $invoices
 */
class PaymongoClient
{
    /** SDK version */
    public const VERSION = '1.0.0';

    /** @var array<string, mixed> Configuration options */
    public array $config = [];
    
    public ServiceFactory $serviceFactory;
    
    /** PayMongo API key used for authenticating and performing PayMongo API operations. */
    public string $apiKey;
    
    /** PayMongo API Base URL */
    public string $apiBaseUrl = 'https://api.paymongo.com';
    
    /** PayMongo API current API version */
    public string $apiVersion = 'v1';

    /**
     * @param string $apiKey The API key for authentication
     * @param array<string, mixed> $config Configuration options
     */
    public function __construct(string $apiKey = '', array $config = [])
    {
        $this->apiKey = $apiKey;
        $this->config = array_merge([
            'api_key' => $apiKey,
            'timeout' => null,
            'connect_timeout' => null,
            'idempotency_key' => null,
            'http_headers' => [],
            'http_client' => null,
        ], $config);

        if (array_key_exists('api_base_url', $config)) {
            $this->apiBaseUrl = $config['api_base_url'];
        }

        if (array_key_exists('api_version', $config)) {
            $this->apiVersion = $config['api_version'];
        }

        $this->serviceFactory = new ServiceFactory();
    }

    /**
     * Get the full API URL with version.
     */
    public function getApiUrl(): string
    {
        return $this->apiBaseUrl . '/' . $this->apiVersion;
    }

    /**
     * Build a URL for an API endpoint.
     */
    public function buildUrl(string $path = ''): string
    {
        return $this->apiBaseUrl . '/' . $this->apiVersion . $path;
    }

    /**
     * Magic getter for accessing services.
     */
    public function __get(string $name): BaseService
    {
        $service = $this->serviceFactory->get($name);

        return new $service($this);
    }
}
