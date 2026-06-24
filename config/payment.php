<?php

return [
    'bank_name' => env('PAYMENT_BANK_NAME', 'Belum dikonfigurasi'),

    'account_number' => env(
        'PAYMENT_ACCOUNT_NUMBER',
        'Belum dikonfigurasi'
    ),

    'account_holder' => env(
        'PAYMENT_ACCOUNT_HOLDER',
        'SasiVerse'
    ),
];
