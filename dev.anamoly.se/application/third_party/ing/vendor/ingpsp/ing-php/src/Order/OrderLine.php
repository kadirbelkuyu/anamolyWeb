<?php

namespace GingerPayments\Payment\Order;

use Ramsey\Uuid\Uuid;
use Assert\Assertion as Guard;

use GingerPayments\Payment\Order\OrderLine\Ean;
use GingerPayments\Payment\Order\OrderLine\Url;
use GingerPayments\Payment\Order\OrderLine\Type;
use GingerPayments\Payment\Order\OrderLine\Name;
use GingerPayments\Payment\Order\OrderLine\Amount as OrderLineAmount;
use GingerPayments\Payment\Order\OrderLine\Currency;
use GingerPayments\Payment\Order\OrderLine\Quantity;
use GingerPayments\Payment\Order\OrderLine\ImageUrl;
use GingerPayments\Payment\Order\OrderLine\DiscountRate;
use GingerPayments\Payment\Order\OrderLine\VatPercentage;
use GingerPayments\Payment\Order\OrderLine\MerchantOrderLineId;

final class OrderLine
{
    /**
     * @var Uuid|null
     */
    private $id;

    /**
     * @var Ean|null
     */
    private $ean;

    /**
     * @var Url|null
     */
    private $url;

    /**
     * @var Name|null
     */
    private $name;

    /**
     * @var Type|null
     */
    private $type;

    /**
     * @var OrderLineAmount|null
     */
    private $amount;

    /**
     * @var Currency|null
     */
    private $currency;

    /**
     * @var Quantity|null
     */
    private $quantity;

    /**
     * @var ImageUrl|null
     */
    private $imageUrl;

    /**
     * @var DiscountRate|null
     */
    private $discountRate;

    /**
     * @var VatPercentage|null
     */
    private $vatPercentage;

    /**
     * @var MerchantOrderLineId|nulL
     */
    private $merchantOrderLineId;

    /**
     * @param array $orderLine
     * @return OrderLine
     */
    public static function fromArray(array $orderLine)
    {
        Guard::keyExists($orderLine, 'name');
        Guard::keyExists($orderLine, 'amount');
        Guard::keyExists($orderLine, 'quantity');
        Guard::keyExists($orderLine, 'currency');
        Guard::keyExists($orderLine, 'vat_percentage');
        Guard::keyExists($orderLine, 'merchant_order_line_id');

        return new static(
            array_key_exists('id', $orderLine) ? Uuid::fromString($orderLine['id']) : null,
            array_key_exists('ean', $orderLine) ? Ean::fromString($orderLine['ean']) : null,
            array_key_exists('url', $orderLine) ? Url::fromString($orderLine['url']) : null,
            array_key_exists('name', $orderLine) ? Name::fromString($orderLine['name']) : null,
            array_key_exists('type', $orderLine) ? Type::fromString($orderLine['type']) : null,
            array_key_exists('amount', $orderLine) ? OrderLineAmount::fromInteger($orderLine['amount']) : null,
            array_key_exists('currency', $orderLine) ? Currency::fromString($orderLine['currency']) : null,
            array_key_exists('quantity', $orderLine) ? Quantity::fromInteger($orderLine['quantity']) : null,
            array_key_exists('image_url', $orderLine) ? ImageUrl::fromString($orderLine['image_url']) : null,
            array_key_exists('discount_rate',
                $orderLine) ? DiscountRate::fromInteger($orderLine['discount_rate']) : null,
            array_key_exists('vat_percentage',
                $orderLine) ? VatPercentage::fromInteger($orderLine['vat_percentage']) : null,
            array_key_exists('merchant_order_line_id',
                $orderLine) ? MerchantOrderLineId::fromString($orderLine['merchant_order_line_id']) : null
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'ean' => $this->getEan(),
            'url' => $this->getUrl(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'quantity' => $this->getQuantity(),
            'image_url' => $this->getImageUrl(),
            'discount_rate' => $this->getDiscountRate(),
            'vat_percentage' => $this->getVatPercentage(),
            'merchant_order_line_id' => $this->getMerchantOrderLineId(),
        ];
    }

    /**
     * @return Uuid|null
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getId()
    {
        return ($this->id() !== null) ? $this->id()->toString() : null;
    }

    /**
     * @return Ean|null
     */
    public function ean()
    {
        return $this->ean;
    }

    /**
     * @return null|string
     */
    public function getEan()
    {
        return ($this->ean() !== null) ? $this->ean()->toString() : null;
    }

    /**
     * @return Url|null
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return ($this->url() !== null) ? $this->url()->toString() : null;
    }

    /**
     * @return Name|null
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return ($this->name() !== null) ? $this->name()->toString() : null;
    }

    /**
     * @return Type|null
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return ($this->type() !== null) ? $this->type()->toString() : null;
    }

    /**
     * @return OrderLineAmount|null
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return int|null
     */
    public function getAmount()
    {
        return ($this->amount() !== null) ? $this->amount()->toInteger() : null;
    }

    /**
     * @return Currency|null
     */
    public function currency()
    {
        return $this->currency;
    }

    /**
     * @return null|string
     */
    public function getCurrency()
    {
        return ($this->currency() !== null) ? $this->currency()->toString() : null;
    }

    /**
     * @return Quantity|null
     */
    public function quantity()
    {
        return $this->quantity;
    }

    /**
     * @return int|null
     */
    public function getQuantity()
    {
        return ($this->quantity() !== null) ? $this->quantity()->toInteger() : null;
    }

    /**
     * @return ImageUrl|null
     */
    public function imageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return null|string
     */
    public function getImageUrl()
    {
        return ($this->imageUrl() !== null) ? $this->imageUrl()->toString() : null;
    }

    /**
     * @return DiscountRate|null
     */
    public function discountRate()
    {
        return $this->discountRate;
    }

    /**
     * @return int|null
     */
    public function getDiscountRate()
    {
        return ($this->discountRate() !== null) ? $this->discountRate()->toInteger() : null;
    }

    /**
     * @return VatPercentage|null
     */
    public function vatPercentage()
    {
        return $this->vatPercentage;
    }

    /**
     * @return int|null
     */
    public function getVatPercentage()
    {
        return ($this->vatPercentage() !== null) ? $this->vatPercentage()->toInteger() : null;
    }

    /**
     * @return MerchantOrderLineId|nulL
     */
    public function merchantOrderLineId()
    {
        return $this->merchantOrderLineId;
    }

    /**
     * @return null|string
     */
    public function getMerchantOrderLineId()
    {
        return ($this->merchantOrderLineId() !== null) ? $this->merchantOrderLineId()->toString() : null;
    }

    /**
     * OrderLine constructor.
     *
     * @param Uuid|null $id
     * @param Ean|null $ean
     * @param Url|null $url
     * @param Name|null $name
     * @param Type|null $type
     * @param OrderLineAmount|null $amount
     * @param Currency|null $currency
     * @param Quantity|null $quantity
     * @param ImageUrl|null $imageUrl
     * @param DiscountRate|null $discountRate
     * @param VatPercentage|null $vatPercentage
     * @param MerchantOrderLineId|null $merchantOrderLineId
     */
    private function __construct(
        Uuid $id = null,
        Ean $ean = null,
        Url $url = null,
        Name $name = null,
        Type $type = null,
        OrderLineAmount $amount = null,
        Currency $currency = null,
        Quantity $quantity = null,
        ImageUrl $imageUrl = null,
        DiscountRate $discountRate = null,
        VatPercentage $vatPercentage = null,
        MerchantOrderLineId $merchantOrderLineId = null
    ) {
        $this->id = $id;
        $this->ean = $ean;
        $this->url = $url;
        $this->name = $name;
        $this->type = $type;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->quantity = $quantity;
        $this->imageUrl = $imageUrl;
        $this->discountRate = $discountRate;
        $this->vatPercentage = $vatPercentage;
        $this->merchantOrderLineId = $merchantOrderLineId;
    }
}
