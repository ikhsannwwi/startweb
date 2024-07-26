<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\admin\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Swift_SmtpTransport;
use Swift_Mailer;

class DynamicSMTP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $smtpSettings = $this->getSMTPSettings();

        // Atur konfigurasi mail di runtime
        Config::set('mail.mailer', 'smtp');
        Config::set('mail.host', $smtpSettings['host']);
        Config::set('mail.port', $smtpSettings['port']);
        Config::set('mail.username', $smtpSettings['username']);
        Config::set('mail.password', $smtpSettings['password']);
        Config::set('mail.encryption', $smtpSettings['encryption']);
        Config::set('mail.from.address', $smtpSettings['from_address']);
        Config::set('mail.from.name', $smtpSettings['from_name']);

        $this->resetMailTransport($smtpSettings);

        return $next($request);
    }

    private function getSMTPSettings()
    {
        $settings = Setting::pluck('value', 'name')->toArray();

        return [
            'host' => $settings['smtp_host_admin'] ?? '127.0.0.1',
            'port' => intval($settings['smtp_port_admin'] ?? 1025),
            'username' => $settings['smtp_user_admin'] ?? '',
            'password' => $settings['smtp_password_admin'] ?? '',
            'encryption' => $settings['smtp_security_admin'] ?? 'tls',
            'from_address' => $settings['smtp_user_admin'] ?? '',
            'from_name' => $settings['nama_app_admin'] ?? '',
        ];
    }

    private function resetMailTransport($smtpSettings)
    {
        $transport = new Swift_SmtpTransport($smtpSettings['host'], $smtpSettings['port'], $smtpSettings['encryption']);
        $transport->setUsername($smtpSettings['username']);
        $transport->setPassword($smtpSettings['password']);

        $mailer = new Swift_Mailer($transport);

        Mail::setSwiftMailer($mailer);
    }
}
