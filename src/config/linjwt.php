<?php
return [
    'max_age' => env("JWT_MAX_AGE", 3600),
    'refresh_at' => env("JWT_REF_AT", 30),
    'secret' => env("JWT_SECRET", 'Ic5m3y2TW1JMrpLrAUi2rCDMQepuFJZuC2gX6CFluZS3GfzI41lWufl0mBgSqO9P'),
];
