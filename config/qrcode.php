<?php

return [

    'size' => env('QRCODE_SIZE', 200),

    'encoding' => env('QRCODE_ENCODING', 'UTF-8'),

    'error_correction' => env('QRCODE_ERROR_CORRECTION', 'M'),

    'margin' => env('QRCODE_MARGIN', 1),

];
