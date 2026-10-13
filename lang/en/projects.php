<?php

return [

    'index' => [
        'title' => 'Projects',
        'lead' => 'Audits, efficiency work, thermal simulation and renewable integration, delivered between 2022 and 2024.',
        'count' => ':count project|:count projects',
        'empty' => 'No project in this category yet.',
        'filter_label' => 'Filter by field',
        'all' => 'All',
    ],

    'categories' => [
        'audit' => 'Audit',
        'efficiency' => 'Efficiency',
        'renewable' => 'Renewable',
        'simulation' => 'Simulation',
        'management' => 'Management',
    ],

    'detail' => [
        'context' => 'Context',
        'approach' => 'Method',
        'results' => 'Measured results',
        'tools' => 'Methods and tools',
        'client' => 'Client',
        'year' => 'Year',
        'location' => 'Location',
        'scope' => 'Scope',
        'next' => 'Next project',
        'previous' => 'Previous project',
        'cta_title' => 'Got a comparable site to assess?',
        'cta_text' => 'Describe the building or the process, and I will tell you what an audit can realistically target.',
    ],

    'metrics' => [
        'consumption' => 'Consumption',
        'energy' => 'Energy saved',
        'payback' => 'Payback period',
        'power' => 'Installed capacity',
        'autonomy' => 'Self-sufficiency',
        'co2' => 'CO₂ avoided over 20 years',
        'cop' => 'Seasonal COP',
        'uptime' => 'Service continuity',
        'sites' => 'Scope',
        'epi' => 'Performance indicator',
        'certification' => 'Certification',
        'surface' => 'Floor area modelled',
        'bbio' => 'Bioclimatic demand',
        'comfort' => 'Discomfort degree-hours',
        'households' => 'Households connected',
        'storage' => 'Storage capacity',
    ],

    'items' => [

        'campus_audit' => [
            'title' => 'Energy audit of a university campus',
            'excerpt' => 'Twelve buildings, 48,000 m² and a bill climbing every year. The audit isolated three sources that alone carried 80 % of the available savings.',
            'client' => 'Public higher education institution',
            'location' => 'Cotonou, Benin',
            'scope' => '12 buildings — 48,000 m²',
            'context' => 'The campus electricity bill was rising 9 % a year with no new floor area. There was no sub-metering at all, so nobody could say which building or which end use was driving the drift.',
            'approach' => 'I installed 34 temporary metering points for six weeks, ran a thermographic survey of the envelope and modelled the two heaviest buildings in Pleiades + COMFIE. The readings were cross-checked against actual occupancy schedules, then delivered as a Power BI dashboard handed over to the technical team.',
            'results' => 'Three sources held most of the potential: air conditioning left running outside occupancy, circulation lighting, and a faulty control loop on the lecture hall air handling unit. The action plan was launched in two phases.',
            'result_items' => [
                'Annual consumption down 34 % after the first phase',
                '1,870 MWh saved over the following full year',
                'Average payback of 2.8 years on the actions taken',
                'Monthly monitoring dashboard now run independently by the technical team',
            ],
        ],

        'logistics_pv' => [
            'title' => 'Self-consumption solar plant on a logistics platform',
            'excerpt' => '1.2 MWp on the roof of a logistics platform whose load curve lines up almost perfectly with solar yield.',
            'client' => 'Private logistics operator',
            'location' => 'Sèmè-Podji, Benin',
            'scope' => '9,400 m² roof — 1.2 MWp',
            'context' => 'The platform runs from 6 a.m. to 8 p.m., with consumption dominated by chilled storage and materials handling. That strongly daytime profile suited self-consumption without storage.',
            'approach' => 'Load curve analysis at 10-minute resolution over a full year, yield simulation in PVsyst with an on-site shading survey, then sizing arbitrated between self-production and self-consumption ratios. I ran the grid connection study and wrote the tender specification for the works.',
            'results' => 'The chosen size covers two thirds of annual demand with no export to the grid, which avoids the tariff constraints that come with surplus.',
            'result_items' => [
                '1.2 MWp installed on an existing roof, with no structural reinforcement',
                '68 % of annual demand covered by solar generation',
                '1,450 tonnes of CO₂ avoided over the plant lifetime',
                'Monitoring at 15-minute resolution, with automatic under-production alerts',
            ],
        ],

        'hospital_cooling' => [
            'title' => 'Cooling plant retrofit at a regional hospital',
            'excerpt' => 'Two ageing chillers, a collapsed COP and zero tolerance for downtime. The retrofit ran without closing the operating theatre.',
            'client' => 'Regional hospital',
            'location' => 'Porto-Novo, Benin',
            'scope' => '2 × 900 kW cooling — 18,000 m²',
            'context' => 'The cooling plant served the operating theatre and the inpatient wards. Both chillers, installed in 2004, ran permanently at part load with a measured seasonal COP of 2.1.',
            'approach' => 'Three months of plant instrumentation (absorbed power, supply and return temperatures, flow rates), reconstruction of the cooling load curve, then modelling of four retrofit scenarios. The works were phased together with the biomedical department to guarantee theatre continuity.',
            'results' => 'The selected scenario combines a correctly sized variable-speed chiller, a floating water temperature setpoint and control through the building management system.',
            'result_items' => [
                'Cooling plant consumption down 41 %',
                'Seasonal COP raised from 2.1 to 4.3',
                'No service interruption during the works',
                'Plant brought in line with building automation requirements',
            ],
        ],

        'food_iso' => [
            'title' => 'Energy management system across four food plants',
            'excerpt' => 'Four factories, four ways of counting energy. ISO 50001 was first used to make the sites comparable with each other.',
            'client' => 'Regional food manufacturing group',
            'location' => 'Benin and Togo',
            'scope' => '4 production sites',
            'context' => 'Group management wanted to steer energy at group level, but every site had its own metering, its own units and no shared indicator.',
            'approach' => 'Site-by-site definition of scope and significant energy uses, a consistent metering plan, then energy performance indicators normalised against production tonnage. I trained each site’s energy officer and ran the first annual energy review.',
            'results' => 'Certification was achieved at the first audit. The real gain, though, was comparability: the gaps between sites revealed room for improvement nobody had suspected.',
            'result_items' => [
                'ISO 50001 certification achieved with no major non-conformity',
                'Energy performance indicator improved by 12 % in 18 months',
                'Consistent metering plan across all four sites',
                'Four energy officers trained and running the monthly review themselves',
            ],
        ],

        'housing_simulation' => [
            'title' => 'Dynamic thermal simulation of 180 homes',
            'excerpt' => 'A residential scheme to bring into regulatory compliance, with summer comfort to hold without air conditioning.',
            'client' => 'Property developer',
            'location' => 'Lyon, France',
            'scope' => '180 homes — 14,200 m²',
            'context' => 'The scheme had to satisfy all three regulatory indicators on a dense urban site, where heavy shading and noise constraints limited night ventilation.',
            'approach' => 'The whole scheme was modelled in CYPETHERM, with dynamic simulation of the most exposed homes. I tested eleven variants of envelope, solar shading and purge ventilation strategies before arbitrating with the architect and the structural engineer.',
            'results' => 'The chosen variant keeps the large living room glazing, with adjustable external louvres and mechanical night purge ventilation.',
            'result_items' => [
                'Bioclimatic demand 23 % below the regulatory requirement',
                'Discomfort degree-hours brought down to 312, against a 1,250 ceiling',
                'No air-conditioned home in the delivered scheme',
                'Eleven variants compared on an explicit cost-versus-comfort basis',
            ],
        ],

        'rural_microgrid' => [
            'title' => 'Hybrid solar microgrid for three rural communities',
            'excerpt' => '480 kWp, 920 kWh of batteries and 1,250 households connected — sized against demand that did not exist yet.',
            'client' => 'Rural electrification programme',
            'location' => 'Rural Benin',
            'scope' => '3 communities — 1,250 households',
            'context' => 'None of the three communities was connected to the national grid. Sizing a microgrid there means estimating future demand with no consumption history to work from.',
            'approach' => 'Field survey of end uses and ability to pay, three ten-year demand growth scenarios, then techno-economic optimisation in HOMER Pro. The chosen design favours modularity: generation capacity can be doubled without touching the electrical architecture.',
            'results' => 'The microgrid went live with deliberate headroom for growth, and a tariff set against the ability to pay measured during the survey.',
            'result_items' => [
                '480 kWp of solar generation and 920 kWh of storage',
                '1,250 households connected in the first phase',
                'Architecture extensible to 960 kWp with no network rework',
                'Levelised cost of generation stable over 20 years',
            ],
        ],

    ],

];
