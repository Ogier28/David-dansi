<?php

return [

    'eyebrow' => 'Contact',
    'title' => 'Let’s talk about your site',
    'lead' => 'Describe the building, the process or the project. I reply within two working days with a first assessment and, where it fits, a proposed scope of work.',

    'details' => [
        'email' => 'Email',
        'phone' => 'Phone',
        'location' => 'Based in',
        'availability' => 'Availability',
        'availability_value' => 'Assignments across West Africa, Europe and remote',
        'response' => 'Response time',
        'response_value' => 'Two working days',
    ],

    'form' => [
        'name' => 'Name',
        'name_placeholder' => 'First and last name',
        'email' => 'Email',
        'email_placeholder' => 'you@organisation.com',
        'subject' => 'What is this about',
        'subject_placeholder' => 'Choose a subject',
        'message' => 'Your message',
        'message_placeholder' => 'Type of building or process, floor area or capacity, and what you are trying to achieve.',
        'consent' => 'I agree to this information being used to answer my enquiry.',
        'consent_note' => 'Nothing is shared with third parties, and there is no newsletter.',
        'submit' => 'Send the message',
        'sending' => 'Sending',
        'required' => 'required',
        'optional' => 'optional',
        'honeypot_label' => 'Leave this field empty',
    ],

    'subjects' => [
        'audit' => 'Energy audit',
        'efficiency' => 'Energy efficiency and HVAC',
        'renewable' => 'Solar or microgrid',
        'simulation' => 'Thermal simulation and compliance',
        'other' => 'Something else',
    ],

    'success' => 'Message received. I will reply within two working days to the address you gave.',
    'failure' => 'The message could not be sent. Try again shortly, or write directly to :email.',

    'errors' => [
        'too_fast' => 'The form was submitted faster than a person could fill it in. Take a moment to complete it and try again.',
        'expired' => 'The form was left open too long. Reload the page and send your message again.',
        'spam' => 'The submission was rejected. If you are in fact human, write directly to :email.',
        'captcha' => 'The anti-robot check failed. Reload the page and try again.',
        'throttled' => 'Too many messages from this connection. Try again in :minutes minutes.',
    ],

];
