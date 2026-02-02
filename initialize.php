<?php

declare(strict_types=1);

/**
 * PayMongo PHP SDK Manual Initialization File
 * 
 * Use this file when not using Composer autoload.
 * For Composer users, use: require_once 'vendor/autoload.php';
 */

$srcDir = dirname(__FILE__) . '/src';

// Core Classes
require $srcDir . '/PaymongoClient.php';
require $srcDir . '/PayMongo.php';
require $srcDir . '/ApiResource.php';
require $srcDir . '/Error.php';
require $srcDir . '/SourceError.php';
require $srcDir . '/HttpClient.php';

// Exceptions (load first as they're dependencies)
require $srcDir . '/Exceptions/BaseException.php';
require $srcDir . '/Exceptions/AuthenticationException.php';
require $srcDir . '/Exceptions/InvalidRequestException.php';
require $srcDir . '/Exceptions/InvalidServiceException.php';
require $srcDir . '/Exceptions/RouteNotFoundException.php';
require $srcDir . '/Exceptions/ResourceNotFoundException.php';
require $srcDir . '/Exceptions/UnexpectedValueException.php';
require $srcDir . '/Exceptions/SignatureVerificationException.php';
require $srcDir . '/Exceptions/PublicKeyException.php';
require $srcDir . '/Exceptions/ApiException.php';

// Base Classes
require $srcDir . '/Entities/BaseEntity.php';
require $srcDir . '/Services/BaseService.php';
require $srcDir . '/Services/ServiceFactory.php';

// Entities
require $srcDir . '/Entities/BillingAddress.php';
require $srcDir . '/Entities/Billing.php';
require $srcDir . '/Entities/Listing.php';
require $srcDir . '/Entities/Payment.php';
require $srcDir . '/Entities/PaymentIntent.php';
require $srcDir . '/Entities/PaymentMethod.php';
require $srcDir . '/Entities/Link.php';
require $srcDir . '/Entities/Customer.php';
require $srcDir . '/Entities/Source.php';
require $srcDir . '/Entities/Refund.php';
require $srcDir . '/Entities/Webhook.php';
require $srcDir . '/Entities/Event.php';
require $srcDir . '/Entities/CheckoutSession.php';
require $srcDir . '/Entities/Plan.php';
require $srcDir . '/Entities/Subscription.php';
require $srcDir . '/Entities/Invoice.php';

// Services
require $srcDir . '/Services/CustomerService.php';
require $srcDir . '/Services/PaymentService.php';
require $srcDir . '/Services/PaymentIntentService.php';
require $srcDir . '/Services/PaymentMethodService.php';
require $srcDir . '/Services/LinkService.php';
require $srcDir . '/Services/RefundService.php';
require $srcDir . '/Services/SourceService.php';
require $srcDir . '/Services/WebhookService.php';
require $srcDir . '/Services/CheckoutSessionService.php';
require $srcDir . '/Services/PlanService.php';
require $srcDir . '/Services/SubscriptionService.php';
require $srcDir . '/Services/InvoiceService.php';
