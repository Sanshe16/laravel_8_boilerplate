<?php

// Product Shipping constants
// const FREE_NATIONAL_SHIPPING = 1;
// const SHIPPING_NATIONALLY = 2;

// Categorize relationship with the person whose birthday is saved
if (!defined('RELATION_CATEGORY_FAMILY')) define('RELATION_CATEGORY_FAMILY', 1);
if (!defined('RELATION_CATEGORY_COLLEAGUE')) define('RELATION_CATEGORY_COLLEAGUE', 2);
if (!defined('RELATION_CATEGORY_FRIEND')) define('RELATION_CATEGORY_FRIEND', 3);

return [
    'contact_pagination_count' => 1000,
    'pagination_count' => 15,
    'user_account_status' =>
    [
        'active' => 'active',
        'inactive' => 'inactive',
        'unverified' => 'unverified',
        'blocked' => 'blocked',
    ],
];
