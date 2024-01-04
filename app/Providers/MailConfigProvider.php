<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class MailConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // get email view data in the provider class
        view()->composer('email', function ($view) {

            if (isset(Auth::user()->id)) {

                $settings = Setting::get()->toArray();

                $settings = array_column($settings, 'value', 'name');

                // Check if required SMTP settings are not empty
                if (
                    array_key_exists('smtp_host_admin', $settings) &&
                    array_key_exists('smtp_security_admin', $settings) &&
                    array_key_exists('smtp_port_admin', $settings) &&
                    array_key_exists('smtp_user_admin', $settings) &&
                    array_key_exists('smtp_password_admin', $settings)
                ) {
                    $config = [
                        'driver'     => 'smtp',
                        'host'       => $settings['smtp_host_admin'],
                        'port'       => $settings['smtp_security_admin'],
                        'username'   => $settings['smtp_port_admin'],
                        'password'   => $settings['smtp_user_admin'],
                        'encryption' => $settings['smtp_password_admin'],
                        'from'       => ['address' => 'StartWeb', 'name' => 'Anonymous'],
                    ];

                    Config::set('mail', $config);
                }
            }
        });
    }
}