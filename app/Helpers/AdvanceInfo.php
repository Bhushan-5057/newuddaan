<?php

use App\Models\AdvanceInfo;

if (!function_exists('Advance_info')) {
    /**
     * Read an advanced_info setting by label.
     *
     * Loaded once per request and cached in memory - see company_info() for the
     * reasoning and the exact semantics this preserves.
     */
    function Advance_info($label, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            try {
                $settings = [];
                foreach (AdvanceInfo::where('status', 1)->orderBy('id')->get(['label', 'value']) as $row) {
                    if (!array_key_exists($row->label, $settings)) {
                        $settings[$row->label] = $row->value;
                    }
                }
            } catch (\Throwable $e) {
                $settings = [];
                return $default;
            }
        }

        return $settings[$label] ?? $default;
    }
}
