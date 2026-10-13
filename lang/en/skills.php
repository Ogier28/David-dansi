<?php

return [

    'groups' => [
        'simulation' => 'Simulation and thermal studies',
        'renewable' => 'Renewables and storage',
        'data' => 'Data and analysis',
        'design' => 'Design and technical drawing',
    ],

    'levels' => [
        3 => 'Used regularly',
        4 => 'Advanced',
        5 => 'Expert, trains teams',
    ],

    'level_label' => 'Level :level out of 5 — :description',

    'certifications_title' => 'Certifications and qualifications',

    'certifications' => [
        'iso50001' => [
            'title' => 'ISO 50001 Lead Implementer',
            'issuer' => 'Accredited certification body',
        ],
        'thermography' => [
            'title' => 'Infrared thermography level 2',
            'issuer' => 'ISO 18436-7 certified',
        ],
        'energy_referent' => [
            'title' => 'Industrial energy officer',
            'issuer' => 'ATEE programme',
        ],
        'electrical' => [
            'title' => 'Electrical safety authorisation B2V / BR',
            'issuer' => 'NF C18-510 standard',
        ],
    ],

];
