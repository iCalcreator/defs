<?php
/**
 * a sample PHP data script
 *
 * May contain whatever returning an data array
 *
 * The ' - advFileC' (etc) value suffix, only to ease up visability when testing
 */
   /***   collect and adapt data   ***/
$initTime   = @date( 'Y-m-d H:i:s' );
$systemPath = '/opt/system/common/base/';
$vendorPath = 'lib/vendors/';
$locale     = array(
                 'colours'      =>
                   array (
                     'en'       =>
                       array(
                         0      => 'red - advFileC',
                         1      => 'blue - advFileC',
                         2      => 'black - advFileC',
                         3      => 'white - advFileC',
                            ),
                     'sv'       =>
                       array(
                         0      => 'röd - advFileC',
                         1      => 'blå - advFileC',
                         2      => 'svart - advFileC',
                         3      => 'vit - advFileC',
                            ),
                         ),
                 'name'         =>
                   array (
                     'en'       => 'name - advFileC',
                     'sv'       => 'namn - advFileC',
                         ),
                 'title'        =>
                   array (
                     'en'       => 'title - advFileC',
                     'sv'       => 'titel - advFileC',
                         ),
                   );
   /***   organize and return data   ***/
return array (     // first key always nodeid
                   // node 'advFileC' specific data
               'advFileC'       =>
                 array (
                   'nodeid'     => 'advFileC',
                   'advFileC'   => 'advFileC',
                   'modulePath' => 'modules/advFileC/',
                   'initTime'   => $initTime,
                   'colours'    => $locale['colours'],
                   'name'       => $locale['name'],
                   'title'      => $locale['title'],
                       ),
                   // node 'advFileZ' specific data
               'advFileZ'       =>
                 array (
                   'nodeid'     => 'advFileZ',
                   'modulePath' => 'modules/advFileZ/',
                       ),
                   // 'common' data
               'common'         =>
                 array (
                   'nodeid'     => 'common',
                   'basepath'   => $systemPath,
                   'vendorPath' => $vendorPath,
                       ),
             );
