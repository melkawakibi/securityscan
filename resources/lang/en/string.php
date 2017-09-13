<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Descriptions of attacks
    |--------------------------------------------------------------------------
    |
    |
    */


    'SQLi' => 'SQL injection is a type of cyber attack on a database.',
    'SQLi_Attack' => 'You have an error in your SQL syntax;',
    'payload_sql' => '/resources/payload/sqlblind-injection.txt',
    'SQL_Replace' => '__TIME__',

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

    'SQLi_description' => 'SQL Injection is een code injectie techniek dat gebruikt wordt <br> om software applicaties aan te vallen met SQL statements met als <br> doel schade aan te brengen in het software systeem.',
    'XSS_description' => 'Cross-site scripting is een type kwetsbaarheid dat vooral gevonden <br> wordt in webapplicaties. XSS maakt het mogelijk voor hackers om <br> client-side scripting talen te injecteren op webpagina\'s.',

    /*
    | --------------------------------------------------------------------------
    | Models
    | --------------------------------------------------------------------------
    |
    */

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


];
