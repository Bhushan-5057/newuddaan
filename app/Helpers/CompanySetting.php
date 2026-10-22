<?php

use App\Models\CompanyInfo;

if (!function_exists('company_info')) {
    /**
     * Read a company_info setting by label.
     *
     * The whole table is loaded once per request and kept in memory. The views
     * call this ~500 times (39 times in the site header alone), and each call
     * used to be its own SELECT.
     *
     * Semantics deliberately match the old one-query-per-call version:
     *  - duplicate labels resolve to the lowest id (what ->value() returned)
     *  - only a NULL value falls back to $default; '' is returned as ''
     */
    function company_info($label, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            try {
                $settings = [];
                foreach (CompanyInfo::where('status', 1)->orderBy('id')->get(['label', 'value']) as $row) {
                    if (!array_key_exists($row->label, $settings)) {
                        $settings[$row->label] = $row->value;
                    }
                }
            } catch (\Throwable $e) {
                // Never let a settings lookup take the page down (e.g. before
                // the table exists during a fresh migrate).
                $settings = [];
                return $default;
            }
        }

        return $settings[$label] ?? $default;
    }
}

if (!function_exists('public_media')) {
    /**
     * URL for a public-disk file (KYC, products, profile, etc.).
     * Always uses /storage/... and upgrades http→https so admin <img> tags
     * are not blocked as mixed content on the live HTTPS site.
     */
    function public_media(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        $path = str_replace('\\', '/', trim($path));

        if (preg_match('#^https?://#i', $path)) {
            return preg_replace('#^http://#i', 'https://', $path);
        }

        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $url = asset('storage/' . $path);

        return preg_replace('#^http://#i', 'https://', $url);
    }
}
