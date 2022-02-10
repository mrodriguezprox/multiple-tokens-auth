<?php

return [
    'table' => 'dps_api_tokens',

    'token' => [
        /*
         * Amount of minutes token should live
         * Value is in minutes.
        */
        'life_length' => 15,

        /*
         * Amount of minutes left of life when we should extend it
         * Value is in minutes.
        */
        'extend_life_at' => 10,

        /*
         * Amount of characters for tokens.
        */
        'char_length' => 80,
    ],

    /**
     * Set to true or false to enable/disable token hashing.
     * When set to null, it will default to the auth.guards.api.hash config var.
     */
    'hash' => null,
];
