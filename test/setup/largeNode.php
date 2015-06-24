<?php
/**
 * large node - setup for Database access , node largeNode
 *
 *
 **/
return
  array(
    'nodeid' => 'largeNode',
    'source' =>
      array(
         // specification for source 'section0'
         array(
           'nodeid'     => 'section0',
           'others'     => 'section1,section2,section3,section4,section5,section6,section7,section8,section9',
           'ttl'        => 1500,
           'source'     => 'mysqli',
           'host'       => 'localhost',
           'username'   => 'defs',
           'passwd'     => 'defs',
           'dbname'     => 'defs',
           'table'      => 'config',        // here mapping table and column names
           'major'      => 'section',
           'key1'       => 'key1',
           'key2'       => 'key2',
           'key3'       => 'key3',
           'key4'       => 'key4',
           'value'      => 'cfgval',
           'ltn1toutf8' => 1,                // PHP convert latin1 to utf-8
              ),
           ),
       );
