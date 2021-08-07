<?php
namespace arakakitatsuki\SimplePayPayPayment\Actions;

use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;

class PayPay
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
                'API_KEY' => config('pay_pay.key'),
                'API_SECRET'=>config('pay_pay.secret'),
                'MERCHANT_ID'=>config('pay_pay.merchant_id'),
            ],config('pay_pay.execution_environment'));
    }

    /**
     * return merchant payment id
     *
     * @return string
     */
    public function getMerchantPaymentId(){
        return uniqid('', true);
    }

    /**
     * create pay pay QR code
     *
     * @return PayPay\OpenPaymentAPI\Client  $response
     */
    public function createQrCode(CreateQrCodePayload $CQCPayload){
        return $this->client->code->createQRCode($CQCPayload);
    }
    /**
     * return pay pay QR code
     *
     * @return PayPay\OpenPaymentAPI\Models\CreateQrCodePayload  $response
     */
    public function returnQrCode(int $price, string $merchant_payment_id){
        $CQCPayload = new CreateQrCodePayload;
        $CQCPayload->setMerchantPaymentId($merchant_payment_id);
        $CQCPayload->setRequestedAt();
        $CQCPayload->setCodeType("ORDER_QR");

        $amount = [
            'amount' => $price,
            "currency" => "JPY"
        ];
        $CQCPayload->setAmount($amount);

        $CQCPayload->setRedirectType('WEB_LINK');

        $CQCPayload->setRedirectUrl(config('pay_pay.redirect_url'));

        return $this->createQrCode($CQCPayload);
    }

    /**
     * fetch already created data of QR code
     *
     * @return PayPay\OpenPaymentAPI\Client  $response
     */
    public function getPaymentDetail(string $merchant_payment_id){
        return $this->client->code->getPaymentDetails($merchant_payment_id);
    }
}