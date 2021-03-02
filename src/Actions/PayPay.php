<?php
namespace arakakitatsuki\SimplePayPayPayment\Actions;

use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;
use PayPay\OpenPaymentAPI\Models\OrderItem;

class PayPay
{

    protected static $client;

    /**
     * build pay pay cliant
     *
     * @return PayPay\OpenPaymentAPI\Client  $client
     */
    public static function createClient(){
        self::$client = new Client([
            'API_KEY' => config('pay_pay.key'),
            'API_SECRET'=>config('pay_pay.secret'),
            'MERCHANT_ID'=>config('pay_pay.merchant_id'),
        ],config('pay_pay.execution_environment'));
        return self::$client;
    }
    
    /**
     * return merchant payment id
     *
     * @return \String
     */
    public static function getMerchantPaymentId(){
        return (string) rand(100000000000000000, 999999999999999999);
    }
    
    /**
     * remember merchant payment id
     *
     * @return \String
     */
    public static function remenberMerchantPaymentId(string $merchant_payment_id){
        session(['merchant_payment_id' => $merchant_payment_id]);
    }

    /**
     * create pay pay QR code
     *
     * @return PayPay\OpenPaymentAPI\Client  $response
     */
    public static function createQrCode(CreateQrCodePayload $CQCPayload){
        $response = self::createClient()->code->createQRCode($CQCPayload);
        return $response['data'];
    }
    /**
     * return pay pay QR code
     *
     * @return PayPay\OpenPaymentAPI\Models\CreateQrCodePayload  $response
     */
    public static function returnQrCode(int $price){
        $CQCPayload = new CreateQrCodePayload;
        $merchant_payment_id = self::getMerchantPaymentId();
        self::remenberMerchantPaymentId($merchant_payment_id);
        $CQCPayload->setMerchantPaymentId($merchant_payment_id);
        $CQCPayload->setRequestedAt();
        $CQCPayload->setCodeType("ORDER_QR");

        $amount = [
            'amount' => $price,
            "currency" => "JPY"
        ];
        $CQCPayload->setAmount($amount);
        // 支払いがウェブブラウザで発生している場合は WEB_LINK になります。
        $CQCPayload->setRedirectType('WEB_LINK');
        // 支払い後のリダイレクト先
        $CQCPayload->setRedirectUrl(config('pay_pay.redirect_url'));

        // QRコードを生成
        return self::createQrCode($CQCPayload);
    }

    /**
     * fetch merchant payment id if merchant payment id is already seted
     *
     * @return \String
     */
    public static function fetchMerchantPaymentId(){
        if (session()->has('merchant_payment_id')){
            return session('merchant_payment_id');
        }
        return ;
    }

     /**
     * fetch already created data of QR code
     *
     * @return PayPay\OpenPaymentAPI\Client  $response
     */
    public static function getPaymentDetail(){
        $merchant_payment_id = self::fetchMerchantPaymentId();
        return self::createClient()->code->getPaymentDetails($merchant_payment_id);
    }

     /**
     * delete session of merchant payment id
     *
     * 
     */
    public static function forgetMerchantPaymentId(){
        session()->forget('merchant_payment_id');
    }

     /**
     * return payment is completed or not
     *
     * @return PayPay\OpenPaymentAPI\Client  $response
     */
    public static function confirmPaymentIsCompletedOrNot(){
        return self::getPaymentDetail()['data']['status'];
    }
}