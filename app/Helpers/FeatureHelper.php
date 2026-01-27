<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class FeatureHelper
{
    /**
     * Check if a feature is enabled for the current plan.
     *
     * @param string $feature
     * @return bool
     */
    public static function isEnabled(string $feature): bool
    {
        $plan = Config::get('app.plan', 'standard');
        $planConfig = Config::get("features.plans.{$plan}");

        if (! $planConfig) {
            return false;
        }

        $features = $planConfig['features'];

        if (isset($features['*']) && $features['*'] === true) {
            return true;
        }

        return $features[$feature] ?? false;
    }

    /**
     * Get the current plan name.
     *
     * @return string
     */
    public static function getCurrentPlan(): string
    {
        return Config::get('app.plan', 'standard');
    }

    /**
     * Get all features for the current plan.
     *
     * @return array
     */
    public static function getEnabledFeatures(): array
    {
        $plan = self::getCurrentPlan();
        return Config::get("features.plans.{$plan}.features", []);
    }
}
