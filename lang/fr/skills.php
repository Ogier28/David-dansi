<?php

return [

    'groups' => [
        'simulation' => 'Simulation et études thermiques',
        'renewable' => 'Renouvelables et stockage',
        'data' => 'Données et analyse',
        'design' => 'Conception et dessin technique',
    ],

    'levels' => [
        3 => 'Usage régulier',
        4 => 'Maîtrise avancée',
        5 => 'Expert, formation d’équipes',
    ],

    'level_label' => 'Niveau :level sur 5 — :description',

    'certifications_title' => 'Certifications et habilitations',

    'certifications' => [
        'iso50001' => [
            'title' => 'ISO 50001 Lead Implementer',
            'issuer' => 'Organisme de certification accrédité',
        ],
        'thermography' => [
            'title' => 'Thermographie infrarouge niveau 2',
            'issuer' => 'Certification ISO 18436-7',
        ],
        'energy_referent' => [
            'title' => 'Référent énergie en industrie',
            'issuer' => 'Formation ATEE',
        ],
        'electrical' => [
            'title' => 'Habilitation électrique B2V / BR',
            'issuer' => 'Norme NF C18-510',
        ],
    ],

];
