<?php

/*
|--------------------------------------------------------------------------
| Contenu du portfolio
|--------------------------------------------------------------------------
|
| Ce fichier porte la STRUCTURE du contenu (identité, slugs, chiffres,
| catégories, niveaux, dates, images). Tous les TEXTES traduisibles vivent
| dans lang/fr/*.php et lang/en/*.php, référencés par la clé de chaque entrée.
|
| TODO (contenu de démonstration) : remplacer l'identité, les projets, le
| parcours et les témoignages par les données réelles. Voir README.md,
| section « Modifier le contenu ».
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Langues
    |--------------------------------------------------------------------------
    |
    | La première langue sert de langue par défaut quand la négociation avec
    | le navigateur n'aboutit à rien. Ajouter une langue = ajouter une entrée
    | ici, un dossier lang/<code>/ et les slugs d'URL correspondants.
    |
    */

    'locales' => [
        'fr' => [
            'name' => 'Français',
            'short' => 'FR',
            'html' => 'fr-FR',
            'og' => 'fr_FR',
        ],
        'en' => [
            'name' => 'English',
            'short' => 'EN',
            'html' => 'en',
            'og' => 'en_US',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Segments d'URL localisés
    |--------------------------------------------------------------------------
    |
    | Permet /fr/realisations et /en/projects sur le même contrôleur.
    |
    */

    'route_slugs' => [
        'fr' => [
            'projects' => 'realisations',
            'legal' => 'mentions-legales',
            'privacy' => 'politique-de-confidentialite',
            'contact' => 'contact',
        ],
        'en' => [
            'projects' => 'projects',
            'legal' => 'legal-notice',
            'privacy' => 'privacy-policy',
            'contact' => 'contact',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Identité
    |--------------------------------------------------------------------------
    */

    'identity' => [
        'name' => 'Dansi David',
        'initials' => 'DD',
        'email' => 'contact@dansidavid.com',
        'phone' => '+229 01 97 00 00 00',
        'phone_href' => '+2290197000000',
        'location' => 'Cotonou, Bénin',
        'portrait' => 'images/portrait.webp',
        'cv' => null, // ex. 'documents/dansi-david-cv-fr.pdf'
        'socials' => [
            'linkedin' => 'https://www.linkedin.com/in/dansi-david',
            'email' => 'mailto:contact@dansidavid.com',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sections activables
    |--------------------------------------------------------------------------
    |
    | Passer une valeur à false retire la section de la page ET du rail de
    | navigation, sans laisser de trou dans la mise en page.
    |
    */

    'sections' => [
        'testimonials' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Relevés du hero
    |--------------------------------------------------------------------------
    |
    | 'value' est animé au défilement ; 'decimals' fixe la précision affichée.
    |
    */

    'metrics' => [
        ['key' => 'savings', 'value' => 32, 'decimals' => 0, 'prefix' => '−', 'suffix' => ' %'],
        ['key' => 'energy', 'value' => 4200, 'decimals' => 0, 'suffix' => ' MWh'],
        ['key' => 'sites', 'value' => 24, 'decimals' => 0],
        ['key' => 'payback', 'value' => 3.2, 'decimals' => 1, 'suffix' => ' ans'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Domaines d'expertise
    |--------------------------------------------------------------------------
    |
    | 'icon' référence un tracé de resources/views/components/icon.blade.php.
    | 'span' est la largeur de la carte sur une grille de six colonnes : les
    | cartes larges (4) détaillent leurs prestations, les étroites (2) non.
    |
    */

    'expertise' => [
        ['key' => 'audit', 'icon' => 'gauge', 'span' => 4],
        ['key' => 'renewable', 'icon' => 'sun', 'span' => 2],
        ['key' => 'simulation', 'icon' => 'cube', 'span' => 2],
        ['key' => 'efficiency', 'icon' => 'activity', 'span' => 4],
        ['key' => 'management', 'icon' => 'clipboard', 'span' => 3],
        ['key' => 'advisory', 'icon' => 'compass', 'span' => 3],
    ],

    /*
    |--------------------------------------------------------------------------
    | Catégories de projets
    |--------------------------------------------------------------------------
    */

    'project_categories' => ['audit', 'efficiency', 'renewable', 'simulation', 'management'],

    /*
    |--------------------------------------------------------------------------
    | Projets
    |--------------------------------------------------------------------------
    |
    | 'featured' => true place le projet dans la sélection de la page d'accueil.
    | 'metrics' : relevés affichés sur la carte et la page détail.
    | 'image' : WebP dans public/, dimensions 1600 × 1000 (ratio 8/5).
    |
    */

    'projects' => [

        [
            'key' => 'campus_audit',
            'slug' => ['fr' => 'audit-energetique-campus', 'en' => 'campus-energy-audit'],
            'category' => 'audit',
            'year' => 2024,
            'featured' => true,
            'image' => 'images/projects/campus-audit.webp',
            'metrics' => [
                ['key' => 'consumption', 'value' => '−34 %'],
                ['key' => 'energy', 'value' => '1 870 MWh'],
                ['key' => 'payback', 'value' => '2,8 ans'],
            ],
            'tags' => ['ISO 50001', 'Thermographie', 'Pleiades + COMFIE', 'Power BI'],
        ],

        [
            'key' => 'logistics_pv',
            'slug' => ['fr' => 'autoconsommation-photovoltaique-logistique', 'en' => 'logistics-solar-self-consumption'],
            'category' => 'renewable',
            'year' => 2024,
            'featured' => true,
            'image' => 'images/projects/logistics-pv.webp',
            'metrics' => [
                ['key' => 'power', 'value' => '1,2 MWc'],
                ['key' => 'autonomy', 'value' => '68 %'],
                ['key' => 'co2', 'value' => '1 450 t'],
            ],
            'tags' => ['PVsyst', 'Autoconsommation', 'Étude de raccordement', 'Monitoring'],
        ],

        [
            'key' => 'hospital_cooling',
            'slug' => ['fr' => 'production-de-froid-centre-hospitalier', 'en' => 'hospital-cooling-retrofit'],
            'category' => 'efficiency',
            'year' => 2023,
            'featured' => true,
            'image' => 'images/projects/hospital-cooling.webp',
            'metrics' => [
                ['key' => 'consumption', 'value' => '−41 %'],
                ['key' => 'cop', 'value' => '2,1 → 4,3'],
                ['key' => 'uptime', 'value' => '100 %'],
            ],
            'tags' => ['CVC', 'Groupes froid', 'GTB', 'Décret BACS'],
        ],

        [
            'key' => 'food_iso',
            'slug' => ['fr' => 'management-energie-agroalimentaire', 'en' => 'food-industry-energy-management'],
            'category' => 'management',
            'year' => 2023,
            'featured' => false,
            'image' => 'images/projects/food-iso.webp',
            'metrics' => [
                ['key' => 'sites', 'value' => '4 sites'],
                ['key' => 'epi', 'value' => '−12 %'],
                ['key' => 'certification', 'value' => 'ISO 50001'],
            ],
            'tags' => ['ISO 50001', 'Comptage', 'IPE', 'Plan de sobriété'],
        ],

        [
            'key' => 'housing_simulation',
            'slug' => ['fr' => 'simulation-thermique-dynamique-logements', 'en' => 'residential-thermal-simulation'],
            'category' => 'simulation',
            'year' => 2022,
            'featured' => false,
            'image' => 'images/projects/housing-simulation.webp',
            'metrics' => [
                ['key' => 'surface', 'value' => '14 200 m²'],
                ['key' => 'bbio', 'value' => '−23 %'],
                ['key' => 'comfort', 'value' => 'DH 312'],
            ],
            'tags' => ['RE2020', 'STD', 'Confort d\'été', 'CYPETHERM'],
        ],

        [
            'key' => 'rural_microgrid',
            'slug' => ['fr' => 'mini-reseau-solaire-hybride', 'en' => 'hybrid-solar-microgrid'],
            'category' => 'renewable',
            'year' => 2022,
            'featured' => false,
            'image' => 'images/projects/rural-microgrid.webp',
            'metrics' => [
                ['key' => 'households', 'value' => '1 250'],
                ['key' => 'power', 'value' => '480 kWc'],
                ['key' => 'storage', 'value' => '920 kWh'],
            ],
            'tags' => ['Mini-réseau', 'Stockage', 'HOMER Pro', 'Électrification rurale'],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Compétences logicielles
    |--------------------------------------------------------------------------
    |
    | 'level' sur 5 : rendu sous forme de jauge segmentée (type instrument),
    | pas de pourcentage inventé.
    |
    */

    'skills' => [
        'groups' => [
            [
                'key' => 'simulation',
                'items' => [
                    ['name' => 'Pleiades + COMFIE', 'level' => 5],
                    ['name' => 'DesignBuilder / EnergyPlus', 'level' => 4],
                    ['name' => 'CYPETHERM RE2020', 'level' => 4],
                    ['name' => 'TRNSYS', 'level' => 3],
                ],
            ],
            [
                'key' => 'renewable',
                'items' => [
                    ['name' => 'PVsyst', 'level' => 5],
                    ['name' => 'HOMER Pro', 'level' => 4],
                    ['name' => 'RETScreen', 'level' => 4],
                ],
            ],
            [
                'key' => 'data',
                'items' => [
                    ['name' => 'Python (pandas, NumPy)', 'level' => 4],
                    ['name' => 'Power BI', 'level' => 4],
                    ['name' => 'SQL', 'level' => 3],
                ],
            ],
            [
                'key' => 'design',
                'items' => [
                    ['name' => 'AutoCAD', 'level' => 4],
                    ['name' => 'Revit MEP', 'level' => 3],
                    ['name' => 'Caneco BT', 'level' => 3],
                ],
            ],
        ],

        'certifications' => [
            ['key' => 'iso50001', 'year' => 2023],
            ['key' => 'thermography', 'year' => 2022],
            ['key' => 'energy_referent', 'year' => 2021],
            ['key' => 'electrical', 'year' => 2020],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Parcours
    |--------------------------------------------------------------------------
    |
    | 'type' : work | education. 'end' => null signifie « poste actuel ».
    |
    */

    'timeline' => [
        ['key' => 'senior_engineer', 'type' => 'work', 'start' => 2024, 'end' => null],
        ['key' => 'efficiency_manager', 'type' => 'work', 'start' => 2021, 'end' => 2024],
        ['key' => 'thermal_engineer', 'type' => 'work', 'start' => 2019, 'end' => 2021],
        ['key' => 'engineering_degree', 'type' => 'education', 'start' => 2016, 'end' => 2019],
        ['key' => 'physics_degree', 'type' => 'education', 'start' => 2013, 'end' => 2016],
    ],

    /*
    |--------------------------------------------------------------------------
    | Témoignages
    |--------------------------------------------------------------------------
    */

    'testimonials' => [
        ['key' => 'campus_director'],
        ['key' => 'plant_manager'],
        ['key' => 'logistics_ceo'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Formulaire de contact
    |--------------------------------------------------------------------------
    |
    | 'min_delay_seconds' : un humain ne remplit pas le formulaire en moins de
    | ce délai ; en dessous, la soumission est rejetée.
    | 'max_age_seconds'   : au-delà, le jeton de formulaire est périmé.
    |
    */

    'contact' => [
        'recipient' => env('CONTACT_RECIPIENT', 'contact@dansidavid.com'),
        'min_delay_seconds' => (int) env('CONTACT_MIN_DELAY', 3),
        'max_age_seconds' => (int) env('CONTACT_MAX_AGE', 7200),
        'subjects' => ['audit', 'efficiency', 'renewable', 'simulation', 'other'],
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO
    |--------------------------------------------------------------------------
    */

    'seo' => [
        'og_image' => 'images/og-default.png',
        'twitter_handle' => null,
    ],

];
