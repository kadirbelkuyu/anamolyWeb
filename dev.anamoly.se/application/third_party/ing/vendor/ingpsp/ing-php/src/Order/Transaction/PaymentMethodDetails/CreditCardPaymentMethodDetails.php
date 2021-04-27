<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\CreditCardPaymentMethodDetails\CardExpiry;
use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\CreditCardPaymentMethodDetails\CardholderName;
use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\CreditCardPaymentMethodDetails\LiabilityShift;
use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\CreditCardPaymentMethodDetails\TruncatedPan;
use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\CreditCardPaymentMethodDetails\VaultToken;

final class CreditCardPaymentMethodDetails implements PaymentMethodDetails
{

    /**
     * @var CardExpiry|null
     */
    private $cardExpiry;

    /**
     * @var CardholderName|null
     */
    private $cardholderName;

    /**
     * @var LiabilityShift|null
     */
    private $liabilityShift;

    /**
     * @var TruncatedPan|null
     */
    private $truncatedPan;

    /**
     * @var VaultToken|null
     */
    private $vaultToken;

    /**
     * @param array $details
     *
     * @return CreditCardPaymentMethodDetails
     */
    public static function fromArray(array $details)
    {
        return new static(
          array_key_exists('card_expiry', $details)
            ? CardExpiry::fromString($details['card_expiry']) : null,
          array_key_exists('cardholder_name', $details)
            ? CardholderName::fromString($details['cardholder_name']) : null,
          array_key_exists('liability_shift', $details)
            ? LiabilityShift::fromBoolean($details['liability_shift']) : null,
          array_key_exists('truncated_pan', $details)
            ? TruncatedPan::fromString($details['truncated_pan']) : null,
          array_key_exists('vault_token', $details)
            ? VaultToken::fromString($details['vault_token']) : null
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
          "card_expiry"     => ($this->cardExpiry() !== null) ? $this->cardExpiry()->toString() : null,
          "cardholder_name" => ($this->cardholderName() !== null) ? $this->cardholderName()->toString() : null,
          "liability_shift" => ($this->liabilityShift() !== null) ? $this->liabilityShift()->toString() : null,
          "truncated_pan"   => ($this->truncatedPan() !== null) ? $this->truncatedPan()->toString() : null,
          "vault_token"     => ($this->vaultToken() !== null) ? $this->vaultToken()->toString() : null,
        ];
    }

    /**
     * @return CardExpiry|null
     */
    private function cardExpiry()
    {
        return $this->cardExpiry;
    }

    /**
     * @return CardholderName|null
     */
    private function cardholderName()
    {
        return $this->cardholderName;
    }

    /**
     * @return LiabilityShift|null
     */
    private function liabilityShift()
    {
        return $this->liabilityShift;
    }

    /**
     * @return TruncatedPan|null
     */
    private function truncatedPan()
    {
        return $this->truncatedPan;
    }

    /**
     * @return VaultToken|null
     */
    private function vaultToken()
    {
        return $this->vaultToken;
    }


    /**
     * CreditCardPaymentMethodDetails constructor.
     *
     * @param CardExpiry|null     $cardExpiry
     * @param CardholderName|null $cardholderName
     * @param LiabilityShift|null $liabilityShift
     * @param TruncatedPan|null   $truncatedPan
     * @param VaultToken|null     $vaultToken
     */
    public function __construct(
      CardExpiry $cardExpiry = null,
      CardholderName $cardholderName = null,
      LiabilityShift $liabilityShift = null,
      TruncatedPan $truncatedPan = null,
      VaultToken $vaultToken = null
    ) {
        $this->cardExpiry     = $cardExpiry;
        $this->cardholderName = $cardholderName;
        $this->liabilityShift = $liabilityShift;
        $this->truncatedPan   = $truncatedPan;
        $this->vaultToken     = $vaultToken;
    }

}
