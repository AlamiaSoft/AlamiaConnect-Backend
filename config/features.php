<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscription Plans and Features
    |--------------------------------------------------------------------------
    |
    | This file contains the feature mapping for different subscription plans.
    | The feature flags can be used to enable/disable specific UI elements or
    | API endpoints based on the 'APP_PLAN' environment variable.
    |
    */

    'plans' => [
        'standard' => [
            'name' => 'Standard',
            'features' => [
                'leads' => true,
                'contacts' => true,
                'reporting' => 'basic',
                'automation' => false,
            ],
        ],

        'pro' => [
            'name' => 'Pro',
            'features' => [
                'leads' => true,
                'contacts' => true,
                'reporting' => 'advanced',
                'automation' => true,
            ],
        ],

        'enterprise' => [
            'name' => 'Enterprise',
            'features' => [
                '*' => true,
            ],
        ],

        'demo' => [
            'name' => 'Demo',
            'features' => [
                '*' => true,
            ],
        ],
    ],
];
