<?php
/**
 * multi node - file / Database access config for node multiNode
 *
 *
 **/
return
  array(
    'nodeid' => 'multiNode',
    'source' =>
       array(
         // specification for file source 'advFileE'
         array(
           'nodeid'     => 'advFileE',
           'ttl'        => 700,
           'source'     => 'file',
           'path'       => 'data/advFileE.csv',
              ),
         // specification for database source 'advDbC'
         array(
           'nodeid'     => 'advDbC',
           'ttl'        => 800,
           'others'     => 'common',
           'source'     => 'mysqli',
           'host'       => 'localhost',
           'username'   => 'defs',
           'passwd'     => 'defs',
           'dbname'     => 'defs',
           'options'    =>
              array( MYSQLI_INIT_COMMAND =>
                "SET NAMES 'utf8' COLLATE 'utf8_general_ci'; SET CHARSET 'utf8';"
                   ),
              ),
         // specification for file source 'advFileG'
         array(
           'nodeid'     => 'advFileG',
           'source'     => 'file',
           'path'       => 'data/advFileG.php',
              ),
         // specification for database source 'advDbD'
         array(
           'nodeid'     => 'advDbD',
           'ttl'        => 1500,
           'source'     => 'mysqli',
           'host'       => 'localhost',
           'username'   => 'defs',
           'passwd'     => 'defs',
           'dbname'     => 'defs',
           'table'      => 'modules',        // here mapping table and column names
           'major'      => 'name',
           'key1'       => 'primary',
           'key2'       => 'second',
           'key3'       => 'ix',
           'value'      => 'content',
           'ltn1toutf8' => 1,                // PHP convert latin1 to utf-8
              ),
            ),
       );
