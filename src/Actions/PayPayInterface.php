<?php
namespace arakakitatsuki\SimplePayPayPayment\Actions;

use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;

interface PayPayInterface {

    /**
     * return merchant payment id
     *
     * @return string
     */
    public function getMerchantPaymentId();

    /**
     * create pay pay QR code
     *
     * @return PayPay\OpenPaymentAPI\Client  $response
     */
    public function createQrCode(CreateQrCodePayload $CQCPayload);

    /**
     * return pay pay QR code
     *
     * @return PayPay\OpenPaymentAPI\Models\CreateQrCodePayload  $response
     */
    public function returnQrCode(int $price, string $merchant_payment_id);

    /**
     * fetch already created data of QR code
     *
     * @return PayPay\OpenPaymentAPI\Client  $response
     */
    public function getPaymentDetail(string $merchant_payment_id);
}