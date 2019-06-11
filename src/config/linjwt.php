<?php
return [
    'max_age' => env("JWT_MAX_AGE", 60),
    'secret' => env("JWT_SECRET", ''),
];
