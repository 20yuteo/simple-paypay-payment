# simple-paypay-payment

概要

Pay Pay から提供されている　<a href="https://github.com/paypay/paypayopa-sdk-php">paypayopa/php-sdk</a>　を内部で利用し、簡単な設定でさらに使いやすくclass化しています。

利用方法

Composer

下記コマンドを実行して、コンポーザー経由でインストールしてください。

```
composer require arakakitatsuki/simple-paypay-peyment
```

.envにPay Pay for developperの情報を下記の様に入力してください。

```
PAY_PAY_KEY=""
PAY_APY_SECRET=""
PAY_PAY_KEY_FOR_TEST=""
PAY_APY_SECRET_FOR_TEST=""
MERCHANT_ID=""
REDIRECT_URL="http://localhost"
PRODUCTIO_ENV="true"
SANDBOX="false"
```

下記コマンドを実行して、package内のconfigファイルをアプリケーションのconfig/配下にコピーします。

```
php artisan vendor:publish
```

config/pay_pay.phpでテスト環境か本番環境かに合わせて修正してください。デフォルトはテスト環境となっています。

```
<?php

return [
    'key' => env('PAY_PAY_KEY_FOR_TEST'),
    'secret' => env('PAY_APY_SECRET_FOR_TEST'),
    'merchant_id' => env('MERCHANT_ID'),
    'redirect_url' => env('REDIRECT_URL', 'http://localhost'),
    'execution_environment' => env('SANDBOX')
];
```

使用方法

使用したい場所でPayPayクラスを呼び出し,
PayPay::returnQrCode()でQRコードを生成します。<br>
引数には決済する際の料金を入力してください。

```
use arakakitatsuki\SimplePayPayPayment\Actions\PayPay;

class PaymentController extends Controller
{
    public function index()
    {
        $qr_code = PayPay::returnQrCode("1100");
        return view('payment.index', compact("qr_code"));
    }
```

bladeテンプレート内で下記の様に記述するとPay Payの決済ページへのリンクが生成されます。

```
<a href="{{ $qr_code['url'] }}" ></a>
```

PayPay::confirmPaymentIsCompletedOrNot()で決済が完了しているかどうかを確認します。<br>
返り値が"completed"であれば決済完了です。
```
$result = PayPay::confirmPaymentIsCompletedOrNot();
```
