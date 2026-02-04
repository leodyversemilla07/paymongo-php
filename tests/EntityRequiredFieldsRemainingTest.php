<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\Exceptions\UnexpectedValueException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../initialize.php';

final class EntityRequiredFieldsRemainingTest extends TestCase
{
    /**
     * @return array<string, array{class: class-string, required: array<string>, missing: string}>
     */
    public static function entityProvider(): array
    {
        return [
            'Link missing amount' => [
                'class' => Paymongo\Entities\Link::class,
                'required' => ['amount', 'archived', 'currency', 'livemode', 'fee', 'status', 'checkout_url', 'created_at', 'updated_at'],
                'missing' => 'amount',
            ],
            'Source missing type' => [
                'class' => Paymongo\Entities\Source::class,
                'required' => ['type', 'amount', 'currency', 'livemode', 'status', 'redirect', 'created_at', 'updated_at'],
                'missing' => 'type',
            ],
            'Rate missing currency_pair' => [
                'class' => Paymongo\Entities\Rate::class,
                'required' => ['currency_pair', 'provider', 'rate', 'livemode', 'created_at', 'updated_at'],
                'missing' => 'currency_pair',
            ],
            'Quote missing converted_amount' => [
                'class' => Paymongo\Entities\Quote::class,
                'required' => ['amount', 'currency', 'converted_amount', 'converted_currency', 'provider', 'rate_id', 'livemode', 'created_at', 'updated_at'],
                'missing' => 'converted_amount',
            ],
            'Product missing images' => [
                'class' => Paymongo\Entities\Product::class,
                'required' => ['name', 'images', 'livemode', 'created_at', 'updated_at'],
                'missing' => 'images',
            ],
            'InstallmentPlan missing installments' => [
                'class' => Paymongo\Entities\InstallmentPlan::class,
                'required' => ['type', 'installments', 'min_amount', 'max_amount', 'interest_rate', 'issuer', 'status', 'livemode', 'created_at', 'updated_at'],
                'missing' => 'installments',
            ],
            'Trigger missing conditions' => [
                'class' => Paymongo\Entities\Trigger::class,
                'required' => ['action', 'conditions', 'livemode', 'name', 'status', 'created_at', 'updated_at'],
                'missing' => 'conditions',
            ],
            'Rule missing action' => [
                'class' => Paymongo\Entities\Rule::class,
                'required' => ['action', 'conditions', 'livemode', 'name', 'status', 'created_at', 'updated_at'],
                'missing' => 'action',
            ],
            'RuleAttribute missing name' => [
                'class' => Paymongo\Entities\RuleAttribute::class,
                'required' => ['livemode', 'name', 'type', 'created_at', 'updated_at'],
                'missing' => 'name',
            ],
            'Score missing score' => [
                'class' => Paymongo\Entities\Score::class,
                'required' => ['entity_id', 'entity_type', 'livemode', 'score', 'status', 'created_at', 'updated_at'],
                'missing' => 'score',
            ],
            'Review missing entity_id' => [
                'class' => Paymongo\Entities\Review::class,
                'required' => ['entity_id', 'entity_type', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'entity_id',
            ],
            'Requirement missing requirements' => [
                'class' => Paymongo\Entities\Requirement::class,
                'required' => ['requirements', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'requirements',
            ],
            'MerchantCapability missing code' => [
                'class' => Paymongo\Entities\MerchantCapability::class,
                'required' => ['code', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'code',
            ],
            'ReceivingInstitution missing bank_code' => [
                'class' => Paymongo\Entities\ReceivingInstitution::class,
                'required' => ['bank_code', 'name', 'type', 'created_at', 'updated_at'],
                'missing' => 'bank_code',
            ],
            'WalletTransaction missing amount' => [
                'class' => Paymongo\Entities\WalletTransaction::class,
                'required' => ['amount', 'currency', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'amount',
            ],
            'QrPh missing qr_content' => [
                'class' => Paymongo\Entities\QrPh::class,
                'required' => ['amount', 'currency', 'livemode', 'status', 'qr_content', 'created_at', 'updated_at'],
                'missing' => 'qr_content',
            ],
            'QrTransfer missing amount' => [
                'class' => Paymongo\Entities\QrTransfer::class,
                'required' => ['amount', 'currency', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'amount',
            ],
            'CardProgram missing type' => [
                'class' => Paymongo\Entities\CardProgram::class,
                'required' => ['name', 'type', 'currency', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'type',
            ],
            'Cardholder missing email' => [
                'class' => Paymongo\Entities\Cardholder::class,
                'required' => ['email', 'first_name', 'last_name', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'email',
            ],
            'Card missing brand' => [
                'class' => Paymongo\Entities\Card::class,
                'required' => ['brand', 'currency', 'last4', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'brand',
            ],
            'Challenge missing status' => [
                'class' => Paymongo\Entities\Challenge::class,
                'required' => ['status', 'type', 'livemode', 'created_at', 'updated_at'],
                'missing' => 'status',
            ],
            'FileRecord missing filename' => [
                'class' => Paymongo\Entities\FileRecord::class,
                'required' => ['filename', 'livemode', 'purpose', 'status', 'created_at', 'updated_at'],
                'missing' => 'filename',
            ],
            'ChildMerchant missing business' => [
                'class' => Paymongo\Entities\ChildMerchant::class,
                'required' => ['business', 'livemode', 'status', 'requirements', 'created_at', 'updated_at'],
                'missing' => 'business',
            ],
            'Consumer missing email' => [
                'class' => Paymongo\Entities\Consumer::class,
                'required' => ['email', 'first_name', 'last_name', 'livemode', 'status', 'created_at', 'updated_at'],
                'missing' => 'email',
            ],
        ];
    }

    #[DataProvider('entityProvider')]
    public function testMissingRequiredFieldThrows(string $class, array $required, string $missing): void
    {
        $this->expectException(UnexpectedValueException::class);

        $attributes = [];
        foreach ($required as $key) {
            if ($key === $missing) {
                continue;
            }
            $attributes[$key] = $this->dummyValueForKey($key);
        }

        $resource = new ApiResource([
            'data' => [
                'id' => 'test_123',
                'attributes' => $attributes,
            ],
        ]);

        new $class($resource);
    }

    private function dummyValueForKey(string $key): mixed
    {
        $intKeys = [
            'amount', 'amount_due', 'fee', 'net_amount', 'min_amount', 'max_amount', 'score', 'rate',
            'created_at', 'updated_at', 'expires_at',
        ];
        $boolKeys = ['livemode', 'archived'];
        $arrayKeys = ['conditions', 'rules', 'requirements', 'images', 'installments', 'steps', 'entry_ids', 'redirect'];

        if (in_array($key, $intKeys, true)) {
            return 1000;
        }
        if (in_array($key, $boolKeys, true)) {
            return false;
        }
        if (in_array($key, $arrayKeys, true)) {
            return [];
        }
        if ($key === 'currency_pair') {
            return 'PHP/USD';
        }
        if ($key === 'currency' || $key === 'converted_currency') {
            return 'PHP';
        }
        if ($key === 'checkout_url') {
            return 'https://example.com/checkout';
        }
        if ($key === 'qr_content') {
            return '000201';
        }

        return 'test';
    }
}
