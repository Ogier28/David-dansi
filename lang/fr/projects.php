<?php

return [

    'index' => [
        'title' => 'Réalisations',
        'lead' => 'Missions d’audit, d’efficacité énergétique, de simulation et d’intégration de renouvelables, livrées entre 2022 et 2024.',
        'count' => ':count projet|:count projets',
        'empty' => 'Aucun projet dans cette catégorie pour l’instant.',
        'filter_label' => 'Filtrer par domaine',
        'all' => 'Tous',
    ],

    'categories' => [
        'audit' => 'Audit',
        'efficiency' => 'Efficacité',
        'renewable' => 'Renouvelable',
        'simulation' => 'Simulation',
        'management' => 'Management',
    ],

    'detail' => [
        'context' => 'Contexte',
        'approach' => 'Méthode',
        'results' => 'Résultats mesurés',
        'tools' => 'Méthodes et outils',
        'client' => 'Maître d’ouvrage',
        'year' => 'Année',
        'location' => 'Lieu',
        'scope' => 'Périmètre',
        'next' => 'Projet suivant',
        'previous' => 'Projet précédent',
        'cta_title' => 'Un site comparable à faire étudier ?',
        'cta_text' => 'Décrivez-moi le bâtiment ou le process, je vous dis en retour ce qu’un audit peut raisonnablement viser.',
    ],

    'metrics' => [
        'consumption' => 'Consommation',
        'energy' => 'Énergie économisée',
        'payback' => 'Retour sur investissement',
        'power' => 'Puissance installée',
        'autonomy' => 'Taux d’autonomie',
        'co2' => 'CO₂ évité sur 20 ans',
        'cop' => 'COP saisonnier',
        'uptime' => 'Continuité de service',
        'sites' => 'Périmètre',
        'epi' => 'Indicateur de performance',
        'certification' => 'Certification',
        'surface' => 'Surface modélisée',
        'bbio' => 'Besoin bioclimatique',
        'comfort' => 'Degrés-heures d’inconfort',
        'households' => 'Foyers raccordés',
        'storage' => 'Capacité de stockage',
    ],

    'items' => [

        'campus_audit' => [
            'title' => 'Audit énergétique d’un campus universitaire',
            'excerpt' => '12 bâtiments, 48 000 m² et une facture en hausse continue. L’audit a isolé trois gisements qui portent à eux seuls 80 % des économies possibles.',
            'client' => 'Établissement public d’enseignement supérieur',
            'location' => 'Cotonou, Bénin',
            'scope' => '12 bâtiments — 48 000 m²',
            'context' => 'Le campus voyait sa facture électrique progresser de 9 % par an sans extension de surface. Aucun comptage divisionnaire n’existait : impossible de savoir quel bâtiment ou quel usage était responsable de la dérive.',
            'approach' => 'J’ai installé 34 points de comptage temporaires pendant six semaines, mené une campagne de thermographie sur l’enveloppe et modélisé les deux bâtiments les plus consommateurs sous Pleiades + COMFIE. Les relevés ont été recoupés avec les plannings d’occupation réels, puis restitués dans un tableau de bord Power BI remis à l’équipe technique.',
            'results' => 'Trois gisements concentraient l’essentiel du potentiel : la climatisation maintenue hors occupation, l’éclairage des circulations et un défaut de régulation sur la centrale de traitement d’air de l’amphithéâtre. Le plan d’actions a été engagé en deux tranches.',
            'result_items' => [
                'Consommation annuelle réduite de 34 % après la première tranche',
                '1 870 MWh économisés sur l’année pleine suivante',
                'Retour sur investissement moyen de 2,8 ans sur les actions engagées',
                'Tableau de bord de suivi mensuel repris en autonomie par l’équipe technique',
            ],
        ],

        'logistics_pv' => [
            'title' => 'Centrale photovoltaïque en autoconsommation',
            'excerpt' => '1,2 MWc en toiture d’une plateforme logistique dont la courbe de charge coïncide presque parfaitement avec le productible solaire.',
            'client' => 'Opérateur logistique privé',
            'location' => 'Sèmè-Podji, Bénin',
            'scope' => 'Toiture de 9 400 m² — 1,2 MWc',
            'context' => 'La plateforme fonctionne de 6 h à 20 h, avec une consommation dominée par le froid positif et la manutention. Le profil de charge, très diurne, se prêtait à l’autoconsommation sans stockage.',
            'approach' => 'Analyse de la courbe de charge au pas de 10 minutes sur une année, simulation du productible sous PVsyst avec relevé d’ombrage sur site, puis arbitrage du dimensionnement entre taux d’autoproduction et taux d’autoconsommation. J’ai conduit l’étude de raccordement et le cahier des charges de l’appel d’offres travaux.',
            'results' => 'Le dimensionnement retenu couvre les deux tiers des besoins annuels sans injection sur le réseau, ce qui évite la contrainte tarifaire du surplus.',
            'result_items' => [
                '1,2 MWc installés sur une toiture existante, sans renforcement de charpente',
                '68 % des besoins annuels couverts par la production solaire',
                '1 450 tonnes de CO₂ évitées sur la durée de vie de l’installation',
                'Monitoring au pas de 15 minutes, avec alerte automatique sur sous-production',
            ],
        ],

        'hospital_cooling' => [
            'title' => 'Reprise de la production de froid d’un centre hospitalier',
            'excerpt' => 'Deux groupes froid vieillissants, un COP effondré et zéro tolérance à la coupure. La reprise s’est faite sans interruption du bloc.',
            'client' => 'Centre hospitalier régional',
            'location' => 'Porto-Novo, Bénin',
            'scope' => '2 × 900 kW froid — 18 000 m²',
            'context' => 'La production de froid assurait la climatisation du bloc opératoire et des services d’hospitalisation. Les deux groupes, installés en 2004, tournaient en permanence à charge partielle avec un COP saisonnier mesuré à 2,1.',
            'approach' => 'Instrumentation de la production pendant trois mois (puissance absorbée, températures de départ et de retour, débits), reconstitution de la courbe de charge frigorifique, puis modélisation de quatre scénarios de reprise. Le phasage des travaux a été construit avec le service biomédical pour garantir la continuité du bloc.',
            'results' => 'Le scénario retenu combine un groupe à vitesse variable correctement dimensionné, une loi d’eau glissante et le pilotage par la gestion technique du bâtiment.',
            'result_items' => [
                'Consommation de la production de froid réduite de 41 %',
                'COP saisonnier porté de 2,1 à 4,3',
                'Aucune interruption de service pendant les travaux',
                'Mise en conformité avec les obligations du décret BACS',
            ],
        ],

        'food_iso' => [
            'title' => 'Système de management de l’énergie sur quatre sites agroalimentaires',
            'excerpt' => 'Quatre usines, quatre façons de compter l’énergie. L’ISO 50001 a d’abord servi à rendre les sites comparables entre eux.',
            'client' => 'Groupe agroalimentaire régional',
            'location' => 'Bénin et Togo',
            'scope' => '4 sites de production',
            'context' => 'La direction industrielle voulait piloter l’énergie à l’échelle du groupe, mais chaque site disposait de son propre comptage, de ses propres unités et d’aucun indicateur commun.',
            'approach' => 'Définition du périmètre et des usages énergétiques significatifs site par site, construction d’un plan de comptage homogène, puis définition d’indicateurs de performance énergétique rapportés aux tonnages produits. J’ai formé les référents énergie de chaque site et animé la première revue énergétique annuelle.',
            'results' => 'La certification a été obtenue au premier audit. L’intérêt principal a toutefois été la comparabilité : les écarts entre sites ont révélé des marges que personne ne soupçonnait.',
            'result_items' => [
                'Certification ISO 50001 obtenue sans non-conformité majeure',
                'Indicateur de performance énergétique amélioré de 12 % en 18 mois',
                'Plan de comptage homogène sur les quatre sites',
                'Quatre référents énergie formés et autonomes sur la revue mensuelle',
            ],
        ],

        'housing_simulation' => [
            'title' => 'Simulation thermique dynamique de 180 logements',
            'excerpt' => 'Un programme résidentiel à conformer à la RE2020, avec un confort d’été à tenir sans recourir à la climatisation.',
            'client' => 'Promoteur immobilier',
            'location' => 'Lyon, France',
            'scope' => '180 logements — 14 200 m²',
            'context' => 'Le projet devait satisfaire la RE2020 sur les trois indicateurs, dans un contexte urbain dense avec de fortes contraintes d’ombrage et de bruit limitant la ventilation nocturne.',
            'approach' => 'Modélisation de l’ensemble du programme sous CYPETHERM RE2020 et simulation dynamique des logements les plus exposés. J’ai testé onze variantes d’enveloppe, de protections solaires et de stratégies de surventilation avant d’arbitrer avec l’architecte et le bureau de structure.',
            'results' => 'La variante retenue conserve les grandes baies côté séjour, avec des brise-soleil orientables et une surventilation mécanique nocturne.',
            'result_items' => [
                'Besoin bioclimatique inférieur de 23 % à l’exigence réglementaire',
                'Degrés-heures d’inconfort ramenés à 312, pour un plafond réglementaire de 1 250',
                'Aucun logement climatisé dans le programme livré',
                'Onze variantes comparées sur un critère coût / confort explicite',
            ],
        ],

        'rural_microgrid' => [
            'title' => 'Mini-réseau solaire hybride pour trois localités rurales',
            'excerpt' => '480 kWc, 920 kWh de batteries et 1 250 foyers raccordés, dimensionnés sur une demande qui n’existait pas encore.',
            'client' => 'Programme d’électrification rurale',
            'location' => 'Zone rurale, Bénin',
            'scope' => '3 localités — 1 250 foyers',
            'context' => 'Les trois localités n’étaient pas raccordées au réseau national. Dimensionner un mini-réseau suppose d’estimer une demande future, en l’absence de tout historique de consommation.',
            'approach' => 'Enquête de terrain sur les usages et la capacité à payer, construction de trois scénarios de montée en charge sur dix ans, puis optimisation technico-économique sous HOMER Pro. Le dimensionnement retenu privilégie la modularité : la capacité de production peut être doublée sans toucher à l’architecture électrique.',
            'results' => 'Le mini-réseau est entré en service avec une marge de croissance assumée, et une tarification calée sur la capacité à payer relevée lors de l’enquête.',
            'result_items' => [
                '480 kWc de production solaire et 920 kWh de stockage',
                '1 250 foyers raccordés en première phase',
                'Architecture extensible jusqu’à 960 kWc sans reprise du réseau',
                'Coût moyen de production stabilisé sur 20 ans',
            ],
        ],

    ],

];
