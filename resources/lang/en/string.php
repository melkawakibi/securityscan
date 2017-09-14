<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Descriptions of attacks
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
    'XSS_Attack' => '<script>alert(1);</script>',
    'payload_xss' => '/resources/payload/xss.txt',
    'XSS_Replace' => '__XSS__',

    /* 
    | -------------------------------------------------------------------------
    | Report messages
    | -------------------------------------------------------------------------
    |
    | 
    */

    'thread_level_0' => 'Er zijn geen kwetsbaarheden op de website gevonden',
    'thread_level_1' => 'Er zijn geen zorgwekkende kwetsbaarheden op de website gevonden',
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


];
