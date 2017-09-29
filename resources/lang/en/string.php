<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General Information
    |--------------------------------------------------------------------------
    |
    |
    */

    'BlindSQLi' => 'Blind SQL injection is a type of cyber attack on a database.',
    'payload_blind_sql' => '/resources/payload/sqlblind-injection.txt',
    'BlindSQL_Replace' => '__TIME__',

    'SQL' => 'SQL injection is a type of cyber attack on a database.',
    'SQLi_Attack' => 'You have an error in your SQL syntax;',
    'payload_sql' => '/resources/payload/sql-injection.txt',

    'XSS' => 'XSS stands for Cross side scripting, a type of cyber attack that executes javascriptes on the targets website.',
    'XSS_Attack' => '<script>',
    'payload_xss' => '/resources/payload/xss.txt',
    'XSS_Replace' => '__XSS__',

    'report_path' => 'resources/reports/',

    'public_report_path' => 'public/resources/reports/',

    'image_url' => 'http://localhost:8000/images/logo-securityreport-small.png',

    /* 
    | -------------------------------------------------------------------------
    | Report messages
    | -------------------------------------------------------------------------
    |
    | 
    */

    'thread_level_0' => 'Er zijn geen kwetsbaarheden op de website gevonden',
    'thread_level_1' => 'Er zijn geen zorgwekkende kwetsbaarheden op de website gevonden, maar wel een aantal misconfiguraties',
    'thread_level_2' => 'Er zijn één of meer zorgwekkende kwetsbaarheden op de website gevonden',
    'thread_level_3' => 'Er zijn één of meer zeer zorgwekkende kwetsbaarheden op de website gevonden. <br> Er is een kans op explotatie door hackers.',
    'thread_level_4' => 'Er zijn één of meer zeer zorgwekkende kwetsbaarheden op de website gevonden. <br> De kans op exploitatie door hackers is zeer hoog. <br> Hackers kunnen toegang krijgen tot het backend systeem en <br> daarop de databases muteren.',

    /*
    | --------------------------------------------------------------------------
    | Modules description
    | --------------------------------------------------------------------------
    |
    */

    'BlindSQLi_description' => 'Blind SQL Injections is een query injectie techniek waarbij de hacker <br> True en False vragen stelt aan de database om te <br> bepalen of een database kwetsbaar.',

    'SQLi_description' => 'SQL Injection is een query injectie techniek dat gebruikt wordt <br> om software applicaties aan te vallen met SQL statements met als <br> doel data uit een database op te vragen.',

    'XSS_description' => 'Cross-site scripting is een type kwetsbaarheid dat vooral gevonden <br> wordt in webapplicaties. XSS maakt het mogelijk voor hackers om <br> client-side scripting talen te injecteren op webpagina\'s.',

    'Secuirty_Headers_description_Average' => 'De http security headers van dit niveau voorzien een website <br> met een extra laag van beveiliging om aanvallen af te weren en <br> kwetsbaarheidlekken <br> te dichten, kwetsbaarheden met betrekking tot XSS. Het missen van <br> security headers maakt uw website kwetsbaar voor aanvallen.',

    'Secuirty_Headers_description_Low' => 'De http security headers van dit niveau hebben vooral <br> betrekking op misconfiguraties aan de server kant. <br> Dit kan leiden tot het lekken van informatie.',

    /*
    | --------------------------------------------------------------------------
    | Models
    | --------------------------------------------------------------------------
    |
    */

    'BlindSQL' => [

        'module' => 'blindsql',
        'risk' => 'high',
        'wasc_id' => '19',  
    ],

    'SQL' => [

        'module' => 'sql',
        'risk' => 'high',
        'wasc_id' => '19',  
    ],

    'XSS' => [

        'module' => 'xss',
        'risk' => 'high',
        'wasc_id' => '8',  
    ],




   /*
    | --------------------------------------------------------------------------
    | Advies
    | --------------------------------------------------------------------------
    |
    */ 


    'SQL_advies' => 'SQL injectie is helaas nog steeds een veel voorkomende kwetsbaarheid. <br> Maar gelukkig zijn er een aantal oplossingen hiervoor bedacht. <br> Hieronder een lijst met simpele technieken om SQL injecties te verkomen. <br>
        <h5>Regels</h5>
        <ul>
            <li>Gebruik maken van Prepared Statments</li>
            <li>Gebruik maken van opgeslagen procedures (zoals data)</li>
            <li>Een White List maken van toegestaande invoer</li>
            <li>Escaping van gebruikersinput.</li>
        </ul>',

    'XSS_advies' => 'Cross-site scripting is een veel voorkomende client-side kwetsbaarheid waarbij <br> de aanvaller client-site code injecteert op webpagina. <br> Er zijn twee basis regels waar een website aan moet <br> voldoen om dit probleem te verkomen: <br>
        <h5>Regels</h5> 
        <ul>
            <li>Voer nooit onvertrouwde data in, behalve op toegestaande locaties</li>
            <li>Voordat de data wordt ingevoerd moet de HTML Escaped worden</li>
        </ul>',

    'Security_Headers_advies' => 'Secuirty Headers zorgen voor een veilig verkeer tussen de server en de client. <br> Wanneer hier niet gebruik van gemaakt wordt kan het zijn dat hackers het als een kans <br> zien om uw website te exploiteren. Om dit te voorkomen kunt u het volgende oplossing implemeteren: <br>
        <ul>
            <li>X-Content-Type-Options</li>
            <li>X-Frame-Options</li>
            <li>X-XSS-Protection</li>
            <li>Content-Secuirty-Policy</li>
            <li>Public-Key-Pins (SLL)</li>
            <li>HttpOnly</li>
        </ul>',

    /*
    | --------------------------------------------------------------------------
    | Security Headers
    | --------------------------------------------------------------------------
    |
    */

    'Security_Headers' => [

        'X-Content-Type-Options' => [

            'type' => 'Security_header',
            'risk' => 'average',
            'wasc_id' => '15',
            'error' => 'Header missing',
        ],
        'X-Frame-Options' => [

            'type' => 'Security_header',
            'risk' => 'average',
            'wasc_id' => '15',
            'error' => 'Header not set',

        ],
        'X-XSS-Protection' => [

            'type' => 'Security_header',
            'risk' => 'average',
            'wasc_id' => '14',
            'error' => 'Not enabled',

        ],
        'Content-Secuirty-Policy' => [

            'type' => 'Security_header',
            'risk' => 'low',
            'wasc_id' => '14',
            'error' => 'Header not set',


        ],
        'Public-Key-Pins' => [

            'type' => 'Security_header',
            'risk' => 'low',
            'wasc_id' => '14',
            'error' => 'Public Key is missing (Only for SLL)',

        ],
        'HttpOnly' => [

            'type' => 'Security_header',
            'risk' => 'low',
            'wasc_id' => '13',
            'error' => 'No HttpOnly flag',

        ],
        '500 internal server error' => [

            'type' => 'Disclosure',
            'risk' => 'average',
            'wasc_id' => '13',
            'error' => 'Application Error Disclosure',

        ],

    ],

];
