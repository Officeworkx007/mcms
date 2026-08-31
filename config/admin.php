<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Secret Slug
    |--------------------------------------------------------------------------
    |
    | This is the hidden URL segment used to reach the admin login/register
    | screens, e.g. https://yourapp.test/{slug}/login
    | Change ADMIN_PANEL_SLUG in .env anytime — no code changes needed.
    |
    */

    'panel_slug' => env('ADMIN_PANEL_SLUG', 'secret-admin-portal'),

];
