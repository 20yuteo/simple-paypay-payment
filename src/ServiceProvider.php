<?php
namespace arakakitatsuki\SimplePayPayPayment;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * 遅延ローディング
     * @var bool
     */
    // protected $defer = true;
    // public function register()
    // {
    //     $this->mergeConfigFrom($this->configPath(), 'pay_pay');
    //     $this->app->singleton('pay_pay', function($app) {
    //         $config = $app['config'];
    //         return new VoiceText($config->get('pay_pay.api-key'));
    //     });
    // }
    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('pay_pay.php')], 'config');
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/pay_pay.php';
    }
}