<?php

return [

    'eyebrow' => 'Contact',
    'title' => 'Parlons de votre site',
    'lead' => 'Décrivez le bâtiment, le process ou le projet. Je réponds sous 48 heures ouvrées avec un premier avis et, si la mission s’y prête, une proposition de périmètre.',

    'details' => [
        'email' => 'Courriel',
        'phone' => 'Téléphone',
        'location' => 'Basé à',
        'availability' => 'Disponibilité',
        'availability_value' => 'Missions en Afrique de l’Ouest, en Europe et à distance',
        'response' => 'Délai de réponse',
        'response_value' => '48 heures ouvrées',
    ],

    'form' => [
        'name' => 'Nom',
        'name_placeholder' => 'Prénom et nom',
        'email' => 'Courriel',
        'email_placeholder' => 'vous@organisation.com',
        'subject' => 'Objet de la demande',
        'subject_placeholder' => 'Choisissez un objet',
        'message' => 'Votre message',
        'message_placeholder' => 'Type de bâtiment ou de process, surface ou puissance, ce que vous cherchez à obtenir.',
        'consent' => 'J’accepte que ces informations soient utilisées pour répondre à ma demande.',
        'consent_note' => 'Aucune transmission à un tiers, aucune inscription à une lettre d’information.',
        'submit' => 'Envoyer le message',
        'sending' => 'Envoi en cours',
        'required' => 'obligatoire',
        'optional' => 'facultatif',
        'honeypot_label' => 'Ne pas remplir ce champ',
    ],

    'subjects' => [
        'audit' => 'Audit énergétique',
        'efficiency' => 'Efficacité énergétique et CVC',
        'renewable' => 'Photovoltaïque ou mini-réseau',
        'simulation' => 'Simulation thermique et RE2020',
        'other' => 'Autre demande',
    ],

    'success' => 'Message reçu. Je vous réponds sous 48 heures ouvrées à l’adresse indiquée.',
    'failure' => 'Le message n’a pas pu être envoyé. Réessayez dans quelques instants, ou écrivez directement à :email.',

    'errors' => [
        'too_fast' => 'Le formulaire a été soumis trop vite pour être rempli par un humain. Prenez le temps de le compléter et réessayez.',
        'expired' => 'Le formulaire est resté ouvert trop longtemps. Rechargez la page et renvoyez votre message.',
        'spam' => 'La soumission a été rejetée. Si vous êtes bien humain, écrivez directement à :email.',
        'captcha' => 'La vérification anti-robot a échoué. Rechargez la page et réessayez.',
        'throttled' => 'Trop de messages envoyés depuis cette connexion. Réessayez dans :minutes minutes.',
    ],

];
