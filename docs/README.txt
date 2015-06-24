
defs

Author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
Copyright 2015, Kjell-Inge Gustafsson kigkonsult, All rights reserved.
Version   1.0
License   non-commercial use: Creative Commons
          Attribution-NonCommercial-NoDerivatives 4.0 International License
          (http://creativecommons.org/licenses/by-nc-nd/4.0/)
          commercial use :defs1license
Link      http://kigkonsult.se/defs/index.php
Support   http://kigkonsult.se/contact/index.php


PREFACE


This document describes usage of defs, a PHP definition handle class package.

This document is provided by kigkonsult for informational purposes and is
provided on an "as is" basis without any warranties expressed or implied.

Information in this document is subject to change without notice and does not
represent a commitment on the part of kigkonsult. The software described in
this document is provided under a license agreement. The software may be used
only in accordance with the terms of that license agreement. It is against the
law to copy or use the software except as specifically allowed in the license
agreement.

It is the users responsibility to ensure the suitability of the software before
using it. In no circumstances will kigkonsult be responsible for the use of the
software's outcomes or results or any loss or damage of data or programs as a
result of using the software. The use of the software implies acceptance of
these terms.

This document makes previous versions obsolete.

Product names mentioned herein are or may be trademarks or registered
trademarks of their respective owners.



OVERVIEW


defs is a PHP definition handle class package,

managing keyed data like

- configurations
- definitions
- locales
- etc

on a
class / namespace / HTML page / mvc / component / module / plugin / system
(aka node) basis.

The purpose of defs is five-folded:

1. providing keyed data

   'on-demand'

2. using the 'separation-of-concern' paradigm,

   managing configuration/definition/locale sources,
   not mixed up in business logic,

3. contributing higher security

   placing classified or sensitive key data
   outside the webserver document root,

4. consolidating, simultaneously, multiple structured data sources

   csv / ini / php / text / PO source files,
     using PHP scripts as defs data source,
     provides adaptability to any PHP software,

   database source, currently, MySQL/MariaDB,

   access to the lastest updated source data,
     using ttl logic, on each date source base.

5.  supporting

   highly demanding and/or changing environments.


defs operates in a singleton mode, on node basis (as defined above), or as single object instances.

defs may utilize any PEAR Log complient log class, supporting 'log' method.



FILES


  docs/                             documentation directory
      readme.txt                    this file
      licence.txt                   licence

  lib/                              class directory
      defs.class.php                public factory class
      defsBase.class.php            workshop class
      defsCsvHandler.class.php      internal csv file handler class
      defsFileHandler.class.php     internal file handler class
      defsIniHandler.class.php      internal ini file handler class
      defsPhpHandler.class.php      internal PHP file handler class
      defsPOHandler.class.php       internal PO file handler class
      defsTxtHandler.class.php      internal txt file handler class
      defsMysqlHandler.class.php    internal mysql handler class
      defsSourceHandler.class.php   internal defs general source handler
      defsSourceInterface.php       internal defs source handler interface

  sql/                              sql directory
      createTable.sql               create (test) table examples
      loadTable.sql                 load (test) table examples

  test/                             test directory *)
      data/                         test data files directory
      lib/fileCheck/*               test log examination utility package
      log/                          test log files directory
      setup/                        test setup files directory
      index.php                     test start page
      test.*.php                    defs test data and scripts

  composer.json                     composer package directive
  defs.php                          defs invoke script
  readme.md

*) The setup and data filenames in the 'test', 'test/setup' and 'test/data'
directories are named to ease up testing only. The contents in the test data
files, now mixed config and locale data to demonstrate (test) use only, should
be separated in a production environment.



INSTALL

For composer users, add

"kigkonsult/defs": ">=1.0"

in the "require" entry in the root "composer.json" file.

OR

unpack to
- any directory and add it to your include-path
- or unpack to application-specific (classes / src / vendor ...) directory

add

require_once "[path/]defs/defs.php";

to your PHP-script.



DATA SOURCE


defs can use csv/ini/php/txt/PO files and database, currently, MySQL/MariaDB
as data source.

defs can also consolidate, simultaneously, data from multiple data sources.
For use example, examine the test script 'test/setup/multiNode.php'

defs data character set is UTF8.

Database, csv and php file data sources are suitable in a multi node
environment while ini/txt data sources are more single node oriented (one
file for each node).

defs can parse PO language files, supporting the GNU gettext PO files format,
including "plural" msgstr[-s].

For higher security, a recommendation is to place the source file(-s), if any,
outside the webserver document root and with with access rights only for the
server user.

Source data will be fetched lazily, at first 'get'-request and, if using 'ttl'
logic (defs SETUP, below), reloaded after 'ttl' time on single source basis.


Data source structure

Database, csv and php source data are oriented around node-id[-s], one to
four keys/subkeys and value for each <node-id>/<key[s]> entry. Also ini/txt
data files requires node-id, as data grouping 'denominator'.

The php data file may contain whatever returning an data array och provides
an opportunity to convert from any system specific/legacy config (etc) sources.
For php data file example, examine the 'advFileC.php' file in the test/data
directory.

PO source file[-s] are parsed out-of-the-box, any of the "msgid"/"msgid_plural"
along with the "counter" can be used as key.

Retrieving data from a database offers the most flexible (and secure) data
source, using a defs defined table definition or by mapping defs to a source
database table metadata (using 'setup' directives, below).

In the defs defined sql table ('defs' ) are the columns
`nodeid`, `key1`, `key2`, `key3` and `value` used.
Nullified keys are ignored when fetching source data.
For database and (other) table examples, examine the create table and load
table files in the sql directory.

For csv data file example (the second most flexible data source, along with
the php file), examine the data 'advDbA.csv' file in the test/data directory.
For csv source data files, empty (but '0') columns are ignored fetching source
data (last column always 'the value').

Source data ini files is set up of '[sections]' and '='-delimited key /
quoted-value pairs. For examples, examine the data 'advFileB.ini' file in the
test/data directory.

Source data txt files is set up of '='-delimited key / value pairs. Empty and
comment rows will be skipped and double quotes are trimmed from keys and
values. For examples, examine the data 'advDbD.txt' file in the test/data
directory.



defs SETUP


Here describes
- how to invoke defs, the setup
- setup options
- setup directives


You can set up defs using

- simple setup,
    using {string} <node-id>, pointing direct to a source file

- array setup,
    using {array} setup directives

- file setup.
    using {array} ( 'nodeid' => <node-id>, 'setup' => <setup file> )
    pointing to file with setup directives


File setup is a recommendation, due to higher security, placing the setup file
outside the webserver document root and with with access rights only for the
server user. During evaluation or test, simple or array setup is convenient.

defs setup character set is UTF8.

Filenames, used in setup directives, SHOULD have a absolute path. There is no
dependency between setup and data file types (csv/ini/php/txt), files can be
of any type, as long as content corresponds to type.

For setup file examples, examine the test/setup directory.


Setup directives:

All directive keys in lower case

key           value
---           -----
nodeid        {string}     // required, the node identifier in the setup
                           // also (main) identifier for source group values
                           // should NOT contain blanks ([A-Za-z0-9_]).

setup      => {string}     // path and filename to setup directive file

ttl        => {integer}    // how long time to keep data before reload
                           // (milliseconds, 1000 ms = 1 sec),
                           // not set or PHP_INT_MAX, results in no reload
                           // may also exist in source data,
                           //   to set/reset ttl 'on the fly'

others     => {string}     // other group identifiers values to include from
                           // the same source, comma separated list
                           // checked in order after <nodeid>
                           // but no overwrite will ocurr if (first) key exists
                           // for use example, examine 'test/setup/advFileC.php'
                           // and 'test/data/advFileC.php'

source     => {string}     // source driver, 'file' / 'PO' / 'mysqli'

source     => {array}      // specific source directives
                           // (when using multiple sources)
                           // array( nodeid
                                     source
                                     path
                                     ...
                                   )
                           // for use example, examine
                           //   'test/setup/multiNode.php'


path       => {string}     // file data source (absolute) path and filename


                           //  More MySQL database value and use info at
                           //  http://php.net/manual/en/mysqli.real-connect.php
host       => {string}     // required, either a host name or an IP address

username   => {string}     // required, the database user name,
                           // SHOULD only have read rights
                           // to the database and specific table

passwd     => {string}     // required, the database user password

dbname     => {string}     // required, the database name

port       => {string}     // specifies the port number
                           // to attempt to connect to the MySQL server

socket     => {string}     // specifies the socket or named pipe
                           // that should be used.

flags      => {mixed}      // With the parameter flags you can set different
                           // connection options

                           // For options, visit
                           //   http://php.net/manual/en/mysqli.options.php
options    => {array}      //  *[ <opt_key> => <opt_value> ]

ltn1toutf8 => {bool}       // if TRUE,
                           // PHP convert db values from latin1 to utf-8

                           // database table definition mapping
                           // Only if using OTHER table definition than defined
                           //   in sql/createTable.sql, table defs
                           // (example in setup/advDbD.txt file)
                           // the use of the 'table' key (or not)
                           //    enables/disables this feature
table      => {string}     // required, the database table name
major      => {string}     // required, major key   column name
key1       => {string}     // required, minor key1  column name
key2       => {string}     //           minor key2  column name
key3       => {string}     //           minor key3  column name
key4       => {string}     //           minor key4  column name
value      => {string}     // required, THE value   column name


Setup:

defs can be setup in three different ways:

- Simple file setup

Setup to use during evaluation/test, very unsecure.


<?php
...
$nodeInst = defs::getInstance( $nodeid );
...
?>

  $nodeid = {string} node id
                    A {$nodeid}.[csv/ini/php/txt] source data file MUST exist
                    in the same directory.


- Array setup

Setup to use during test, also unsecure.
NOTE, in the setup array directives, a nodeid directive MUST exist!

<?php
...
$nodeInst = defs::getInstance( $setup );
...
?>

  $setup  = {array}

                    database source setup example:

                        array( 'nodeid'    => 'aModule',
                               'others'    => 'common,helpModule',
                               'ttl'       => 800,
                               'source'    => 'mysqli',
                               'host'      => 'localhost',
                               'username'  => 'defs',
                               'passwd'    => 'defs',
                               'dbname'    => 'defs',
                             ),

                    Examine for database source directive examples the test
                      'test.array.db.php'
                    script file in the test directory.

  $setup  = {array}

                    file source setup example:

                        array( 'nodeid'    => 'bModule',
                               'source'    => 'file',
                               'path'      => 'modules/bModule/data/data.php',
                             ),

                    Examine for file source directive examples the test
                      'test.array.file.php'
                    script file in the test directory
                    and especially 'data/advFileC.php' demonstrating
                    adaptibility.

                    For a PO source example,
                        array( 'nodeid'  => 'POtest',
                               'source'  => 'po',
                               'path'    => 'data/testPO.txt',
                             ),


- File setup

Recommended setup to use during in a production environment.
The array, above, placed in a setup file.
NOTE, in the setup file directives, a nodeid directive MUST exist!

<?php
...
$nodeInst = defs::getInstance( $setup );
...
?>

  $setup  = {array}

                        array(
                               'nodeid' => <node id>
                               'setup'  => <setup directive file>
                             )

                    example:
                        array( 'nodeid' => 'cModule',
                               'setup'  => 'modules/cModule/setup/setup.php',
                             );

                    examine for database source directive examples the setup
                      'advDb[A/B/C/D].*' files in the test/setup directory

                    examine for file source directive examples the setup
                      'advFile[A/B/C/D].*' files in the test/setup directory
                        (data files can, of cource, be of any other type)

                    examine for mixed source directive examples the setup
                      'multiNode.php' in the test/setup directory



METHODS

Here describes defs factory class public methods.


defs::getInstance( setup )

                    return a defsBase object instance,
                      singleton on a nodeid basis

                    Using no setup or null (unvalid) setup
                      generates an PHP warning/notice and an unusable instance
                      (test/test.no.setup.php, test/test.null.setup.php)

  setup {mixed}
                    any of the setups as described above


defs::factory( setup )

                    return a defsBase object instance on success,
                           FALSE on error

  setup {mixed}
                    any of the setups as described above


defs::addHandler( handlerType, handler )

  handlerType {string}
  handler     {string}


defs::getHandlers()

                    return
                      {array}, default
                            array( 'csv'    => 'defsCsvHandler',
                                   'file'   => 'defsFileHandler',
                                   'ini'    => 'defsIniHandler',
                                   'php'    => 'defsPhpHandler',
                                   'po'     => 'defsPOHandler',
                                   'txt'    => 'defsTxtHandler',
                                   'mysqli' => 'defsMysqlHandler',
                                 );


Here describes defsBase class public methods.

defsBase::get( [arg1 [, arg2 [, arg3 [, arg4 ]]]] )

                    return {mixed}, keyed value

                    return FALSE at NOT FOUND

                    argument use depending on set source keyed data

  arg1 {mixed}      (string/int)
  arg2 {mixed}      (string/int)
  arg3 {mixed}      (string/int)
  arg4 {mixed}      (string/int)

                    All args case dependent.

                    For PO source data, any of the "msgid"/"msgid_plural"
                    along with the "counter" can be used as key.

                    using no args returns ALL {array}


defsBase::toString()

                    return {string} setup and source data

defsBase::internalToString()

                    return {string} setup data

defsBase::valuesToString()

                    return {string} source data



PROPERTIES

Here describes defs factory class public properties.


defs::$log          {object}, default null
                    (opt) a PEAR Log complient log class



TESTS

After install and init of the database (sql scripts in the "sql" directory),
adapt the "defs.test.config.php" in the "test" directory and fire of
"index.php" in a web browser.

All setup models are testable and the scripts in the setup and data directories
are usable as examples.

During tests, logging and LOG_DEBUG is default (selectable), in a production
environment, LOG_ERR is to recommend.

For examining logs, the kigkonsult "mini"-package fileCheck is included,
invoked in "test/defs.test.config.php" and fileCheck config in
"test/lib/fileCheck/fileCheck.cfg.php" script.


You may also use XBenchmark for performance recording, more info at
http://www.phpclasses.org/package/
  9037-PHP-Log-time-and-resources-during-script-execution.html



DONATE

You can show your appreciation for our free software, and can support future
development by making a donation to the kigkonsult projects.

Make a donation of any size going to kigkonsult.se/contact/index.php#Donate.
Thanks in advance!



SUPPORT


Use the kigkonsult.se support page, kigkonsult.se/contact for queries,
improvement/development issues or professional support and development.
Please note that paid support or consulting service has the highest priority.

kigkonsult offer professional services for software support, design and
new/re-development, customizations and adaptations of PHP/MySQL solutions with
focus on software lifecycle management, including long term utility,
reliability and maintainability.

