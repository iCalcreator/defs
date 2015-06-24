<?php
/**
 * node / Database access config for node advDbp
 *
 *
 **/
 return array( 'nodeid'    => 'advDbp',
               'ttl'       => 1000,
               'others'    => 'common',
               'source'    => 'mysqli',
               'host'      => 'localhost',
               'username'  => 'defs',
               'passwd'    => 'defs',
               'dbname'    => 'defs',
               'options'   => array( MYSQLI_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_general_ci'; SET CHARSET 'utf8';" ),
               'table      => 'config',
               'major      => 'major',
               'key1       => 'key1',
               'key2       => 'key2',
               'key3       => 'key3',
               'key3       => 'key4',
               'value      => 'cfgval',
             );
