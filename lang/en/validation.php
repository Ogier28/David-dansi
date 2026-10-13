<?php

return [

    'required' => 'The :attribute field is required.',
    'email' => 'Enter a valid email address.',
    'string' => 'The :attribute field must be text.',
    'in' => 'The selected :attribute is invalid.',
    'accepted' => 'You need to agree to your information being used before sending the message.',
    'max' => [
        'string' => 'The :attribute field must not exceed :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute field must be at least :min characters.',
    ],

    'attributes' => [
        'name' => 'name',
        'email' => 'email',
        'subject' => 'subject',
        'message' => 'message',
        'consent' => 'consent',
    ],

];
