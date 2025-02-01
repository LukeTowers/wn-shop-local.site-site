<?php

return [
    'plugin' => [
        'name' => 'Core',
        'description' => 'No description provided yet...',
    ],
    'permissions' => [
        'manage_retailers' => 'Manage Retailers',
    ],
    'models' => [
        'general' => [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name' => 'Name',
            'code' => 'Code',
        ],
        'retailer' => [
            'label' => 'Retailer',
            'label_plural' => 'Retailers',
        ],
        'retailercontact' => [
            'label' => 'Retailer Contact',
            'label_plural' => 'Retailer Contacts',
        ],
        'retailercategory' => [
            'label' => 'Retailer Category',
            'label_plural' => 'Retailer Categories',
        ],
    ],
];
