<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MailConfigService
{
    /**
     * Apply dynamic SMTP configuration stored in site_settings.
     */
    public static function applyDynamicConfig(): void
    {
        try {
            $mailer = SiteSetting::get('mail_mailer', config('mail.default', 'smtp'));
            Config::set('mail.default', $mailer);

            $host = SiteSetting::get('mail_host');
            if ($host) {
                Config::set('mail.mailers.smtp.host', $host);
            }

            $port = SiteSetting::get('mail_port');
            if ($port) {
                Config::set('mail.mailers.smtp.port', (int) $port);
            }

            $username = SiteSetting::get('mail_username');
            if ($username !== null) {
                Config::set('mail.mailers.smtp.username', $username);
            }

            $password = SiteSetting::get('mail_password');
            if ($password !== null && $password !== '') {
                Config::set('mail.mailers.smtp.password', $password);
            }

            $encryption = SiteSetting::get('mail_encryption', 'tls');
            if ($encryption === 'ssl' || (int) $port === 465) {
                Config::set('mail.mailers.smtp.scheme', 'smtps');
            } elseif ($encryption === 'tls' || (int) $port === 587) {
                Config::set('mail.mailers.smtp.scheme', null);
            } else {
                Config::set('mail.mailers.smtp.scheme', null);
            }

            $fromAddress = SiteSetting::get('mail_from_address', SiteSetting::get('support_email', 'noreply@fix.com.bd'));
            if ($fromAddress) {
                Config::set('mail.from.address', $fromAddress);
            }

            $fromName = SiteSetting::get('mail_from_name', 'FIX Facility Operations');
            if ($fromName) {
                Config::set('mail.from.name', $fromName);
            }

            // Purge cached mail transport instances so new runtime settings are instantly utilized
            if (app()->bound('mail.manager')) {
                app('mail.manager')->purge('smtp');
            }
        } catch (\Throwable $e) {
            // Graceful fallback during migration / setup
            Log::debug('MailConfigService fallback: ' . $e->getMessage());
        }
    }
}
