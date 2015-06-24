<?php
/**
 * node / Database access config for node advDbC
 *
 *
 **/
 return
   array(
     'nodeid'    => 'advDbC',
     'ttl'       => 1000,
     'others'    => 'common',
     'source'    => 'mysqli',
     'host'      => 'localhost',
     'username'  => 'defs',
     'passwd'    => 'defs',
     'dbname'    => 'defs',
     'options'   =>
       array(
         MYSQLI_INIT_COMMAND =>
           "SET NAMES 'utf8' COLLATE 'utf8_general_ci'; SET CHARSET 'utf8';"
            ),
        );
