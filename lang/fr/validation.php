<?php

/*
| Messages de validation utilisés par le formulaire de contact. Laravel
| complète les clés absentes avec la langue de repli (anglais).
*/

return [

    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Indiquez une adresse électronique valide.',
    'string' => 'Le champ :attribute doit être du texte.',
    'in' => 'La valeur sélectionnée pour :attribute est invalide.',
    'accepted' => 'Vous devez accepter l’utilisation de vos informations pour envoyer le message.',
    'max' => [
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],

    'attributes' => [
        'name' => 'nom',
        'email' => 'courriel',
        'subject' => 'objet',
        'message' => 'message',
        'consent' => 'consentement',
    ],

];
