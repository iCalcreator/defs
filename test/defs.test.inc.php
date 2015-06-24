<?php
/**
 * defs, a PHP key-value definition handle class, managing keyed data
 *
 * @package   defs
 * @copyright Copyright (c) 2015 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @author    Kjell-Inge Gustafsson <ical@kigkonsult.se>
 * @link      http://kigkonsult.se/defs/index.php
 * @license   non-commercial use: Creative Commons
 *            Attribution-NonCommercial-NoDerivatives 4.0 International License
 *            (http://creativecommons.org/licenses/by-nc-nd/4.0/)
 *            commercial use :defs1license
 * @version   1.0
 *
 * defs.test.inc.php
 */
$timeStart = microtime( TRUE );
$sleep     = (float) 0;
$included  = TRUE;
/**********  configuration, start                       ************* */
$memory_get_peak_start1 = memory_get_peak_usage( TRUE );
require_once 'defs.test.config.php';
$memory_get_peak_start2 = memory_get_peak_usage( TRUE );
/**********  configuration, end                         ************* */
/******************************************************************** */
/**********  common functions, start                    ************* */
function creButton( $value, $fileSpec ) {
  echo '<input type="button" value="', $value, '" onClick="goto(this,\'', $fileSpec, '\');" title="test.', $fileSpec, '.php"/>';
}
/******************************************************************** */
function dispCode( $data ) {
  echo '<table class="g"><tr><td>', $data, "</td></tr></table>\n";
}
/******************************************************************** */
function dispLabel( $message, $anchor=null, $prev=null, $next=null ) {
  global $btnPrfx;
  $tdClass = '';
  if( ! empty( $anchor )) {
    echo '<a name="', $anchor, '@', $btnPrfx,  "\"></a>\n";
    $tdClass = ' class="c"';
  }
  echo '<table border=0><tr><td' . $tdClass . '>' . $message. '</td>';
  if( ! empty( $anchor )) {
    echo   '<td class="r wp">';
    if( ! empty( $prev ))
      echo '<a class="info" href="#' . $prev, '@', $btnPrfx . '">[prev]</a>';
    if( ! empty( $next ))
      echo '<a class="info" href="#' . $next, '@', $btnPrfx . '">[next]</a>';
    echo   '<a class="info" href="#top">[top]</a>',
           '<a class="info" href="#down">[down]</a>',
           '</td>';
  }
  echo "</tr></table>\n";
}
/******************************************************************** */
function dispResult( $label, $data, $button=null ) {
  global $btnPrfx;
  if( ! empty( $label ))
    echo '<table><tr><td>// var_export( ', $label, ' )</td></tr><tr><td class="boxBorder p">', nl2br( str_replace( ' ', '&nbsp;', $data )), "</td></tr></table>\n";
  if( ! empty( $button ))
    echo '<table><tr><td class="r wb10"><a class="info" href="#', $button, '@', $btnPrfx . '">[', $button, ']</a><a class="info" href="#top">[top]</a></td></tr></table>', "\n<br><br>\n";
}
/******************************************************************** */
function endPage() {
  global $timeStart, $memory_get_peak_start1, $memory_get_peak_start2, $defsFactory, $sleep;
  $memory_get_peak_start1 = number_format( $memory_get_peak_start1, 0, '', ' ' );
  $memory_get_peak_start2 = number_format( $memory_get_peak_start2, 0, '', ' ' );
  $memory_get_peak_usage  = number_format((float) memory_get_peak_usage( TRUE ), 0, '', ' ' );
  $timesTotal             = number_format(( microtime( TRUE ) - $timeStart ), 4 );
  $timesSleep             = number_format(( $sleep / 1000000 ), 4 );
  $timesExec              = number_format(( microtime( TRUE ) - $timeStart - ($sleep / 1000000 )), 4 );
?>
<br>
<div class="boxBorder">
<table class="info tc" border="0">
<tbody>
<tr>
<td class="t wt30">
<b>defs <?php echo $defsFactory::$version; ?></b><br>
Copyright &copy; 2015<br>
Kjell-Inge Gustafsson kigkonsult<br>
All rights reserved
</td>
<td class="t">
<table class="info tc" border="0">
<tr><td class="wp">Memory php init</td><td class="r wp"><?php echo "$memory_get_peak_start1"; ?></tr>
<tr><td class="wp">after defs incl.</td><td class="r wp"><?php echo "$memory_get_peak_start2"; ?></tr>
<tr><td class="wp">after exec </td><td class="r wp"><?php echo "$memory_get_peak_usage"; ?></tr>
</table>
</td>
<td class="r t">&nbsp;</td>
<td class="r t">
<table class="info tc" border="0">
<tr><td class="r wp">time tot</td><td class="r wp"><?php echo $timesTotal; ?></td></tr>
<tr><td class="r wp">sleep</td><td class="r wp"><?php echo $timesSleep; ?></td></tr>
<tr><td class="r wp">exec</td><td class="r wp"><?php echo $timesExec; ?></td></tr>
</table>
</td>
<!-- td class="b labelt"><?php echo 'Version: <a href="http://www.php.net/" target="_blank">PHP</a> '.PHP_VERSION; ?></td -->
<td class="r wt30">
<a href="http://kigkonsult.se/index.php" title="kigkonsult">kigkonsult</a><br>
<a href="http://kigkonsult.se/contact/index.php" title="contact">kigkonsult contact</a><br>
<a href="http://kigkonsult.se/defs/index.php" title="homepage">defs homepage</a>
</td>
</tbody>
</table>
</div>
<script type="text/javascript">
function goto(a,test) {
  a.style.cursor = 'wait';
  document.body.style.cursor = 'wait';
  var url = window.location.protocol + '//' + window.location.hostname, path = window.location.pathname,v,delim='?';
  url    += path.substring(0, path.lastIndexOf("/")) + '/test.' + test + '.php';
  v       = document.getElementById('lp');
  if(null!=v) {
    url  += delim + 'lp=' + v.options[v.selectedIndex].value;
    delim = '&';
  }
  v       = document.getElementById('invoke');
  if(null!=v)
    url  += delim + 'invoke=' + v.options[v.selectedIndex].value;
  window.location = url;
}
</script>
<a name="down"></a>
</body>
</html>
<?php
  while (@ob_end_flush());
} // end function endPage()
/******************************************************************** */
function theTest( $setup, $nodeid, $dispAll=FALSE, $usleep=0, $next=null ) {
  global $defsFactory, $sleep;
  if( empty( $usleep ))
    $usleep = 1000000; // 1 sec
  dispResult( '$setup', var_export( $setup, TRUE ));
  if( is_array( $setup ) && isset( $setup['setup'] )) {
    $file = $setup['setup'];
    $content = ( is_file( $file  ) && is_readable( $file ) && ( FALSE !== ( $content = file_get_contents( $file )))) ? $content : "file don't exist!";
    dispResult( 'setup file', htmlspecialchars( $content ));
  }
  $nodeInst  = 'node' . $nodeid . 'A'; // create a defs unique node object instance name
  $singleton = ( isset( $_REQUEST['invoke'] ) && ! empty( $_REQUEST['invoke'] )) ? FALSE : TRUE;
  if( isset( $_REQUEST['invoke'] ) && ! empty( $_REQUEST['invoke'] )) {
    dispLabel( 'using defs::factory() method' );
    dispCode(   '$defs = ' . $defsFactory . '::factory( $setup );' );
    $$nodeInst = $defsFactory::factory( $setup );
  }
  else { // singleton
    dispLabel( 'using defs::getInstance() method' );
    dispCode(   '$defs = ' . $defsFactory . '::getInstance( $setup );' );
    $$nodeInst = $defsFactory::getInstance( $setup );
  }
/*
  if( "object" == gettype( $$nodeInst ))
    dispLabel( $nodeInst . ' is a ' . get_class( $$nodeInst ));
*/
  $defsBase = $defsFactory . 'Base';
  if( ! $$nodeInst instanceof $defsBase ) {
    dispLabel( 'unvalid $defs created...' );
    return FALSE;
  }
  dispLabel( 'display setup before 1:st get using toString method' );
  dispCode(   '$res = $defs-&gt;toString();' );
  dispResult( '$res', $$nodeInst->toString());
          /* get/use it (incl load)                     ************* */
  $res       = array();
  dispLabel( 'get value');
  if( FALSE !== ( $res['title'] = $$nodeInst->get( 'title', 'en' )))
    dispCode( "res['title'] = defs::get( 'title', 'en' );" );
  else {
    $res['title'] = $$nodeInst->get( 'title' );
    dispCode( '$res[\'title\'] = $defs-&gt;get( \'title\' );' );
  }
  dispResult( '$res', var_export( $res, TRUE ));
  if( $dispAll ) {
    dispLabel( 'display all values using toString method' );
    dispCode(   '$res = defs-&gt;toString();' );
    dispResult( '$res', $$nodeInst->toString());
  }
  else {
    dispLabel( 'display settings using internalToString method' );
    dispCode(   '$res = defs-&gt;internalToString();' );
    dispResult( '$res', $$nodeInst->internalToString());
  }
          /* ******************************************************** */
  dispCode( "usleep({$usleep}); // microsec" );
  usleep( $usleep );
  $sleep += $usleep;
          /* define another node $nodeid                ************* */
  dispLabel( "<br>set up <b>another</b> node with the same nodeid" );

  $nodeInst  = 'node' . $nodeid . 'B'; // create a defs unique node object instance name
  if( $singleton ) {
    dispLabel( 'using defs::getInstance() method' );
    $$nodeInst = $defsFactory::getInstance( $setup );
    dispCode(   '$defs = defs::getInstance( $setup );' );
  }
  else {
    dispLabel( 'using defs::factory() method' );
    $$nodeInst = $defsFactory::factory( $setup );
    dispCode(   '$defs = defs::factory( $setup );' );
  }
  if( $dispAll ) {
    dispLabel( 'display all values using toString method' );
    dispCode(   '$res = defs-&gt;toString();' );
    dispResult( '$res', $$nodeInst->toString());
  }
  else {
    dispLabel( 'display settings using internalToString method' );
    dispCode(   '$res = defs-&gt;internalToString();' );
    dispResult( '$res', $$nodeInst->internalToString());
  }
          /* get/use it (incl load)                     ************* */
  $res      = array();
  dispLabel( 'get value');
  if( FALSE !== ( $text = $$nodeInst->get( 'You have selected %d file for deletion', 1 ))) {
    dispCode( '$text = $defs-&gt;get( \'You have selected %d file for deletion\', 1 );' );
    dispResult( '$text', var_export( $text, TRUE ));
  }
  if( FALSE !== ( $modulePath = $$nodeInst->get( 'modulePath' ))) {
    dispCode( '$modulePath = $defs-&gt;get( \'modulePath\' );' );
    dispResult( '$modulePath', var_export( $modulePath, TRUE ));
  }
  if( FALSE !== ( $blue = $$nodeInst->get( 'colours', 'en', 2 ))) {
    dispCode( '$blue = $defs-&gt;get( \'colours\', \'en\', 2 );' );
    dispResult( '$blue', var_export( $blue, TRUE ));
  }
  elseif( FALSE !== ( $colours = $$nodeInst->get( 'colours', 'en' ))) {
    dispCode( '$colours = $defs-&gt;get( \'colours\', \'en\' );' );
    dispResult( '$colours', var_export( $colours, TRUE ));
  }
  elseif( FALSE !== ( $res['colours'] = $$nodeInst->get( 'colours' ))) {
    dispCode( '$res[\'colours\'] = $defs-&gt;get( \'colours\' );' );
    dispResult( '$res', var_export( $res, TRUE ));
  }
          /* ******************************************************** */
  dispCode( "usleep({$usleep}); // microsec" );
  usleep( $usleep );
  $sleep += $usleep;
  dispLabel( 'get and count all values' );
  dispCode( '$cnt = count( defs-&gt;get()); ');
  dispResult( '$cnt', count( $$nodeInst->get()) );
  if( $dispAll ) {
    dispLabel( 'display all values using toString method' );
    dispLabel( "if 'ttl', compare 'loadCnt', 'init' and 'last load' here and above" );
    dispCode(   '$res = defs-&gt;toString();' );
    $res = $$nodeInst->toString();
  }
  else {
    dispLabel( 'display settings using internalToString method' );
    dispLabel( "if 'ttl', compare 'loadCnt', 'init' and 'last load' here and above" );
    dispCode(   '$res = defs-&gt;internalToString();' );
    $res = $$nodeInst->internalToString();
  }
  dispResult( '$res', $res, $nodeid );
} // end function theTest(
/******************************************************************** */
function dispOptions( $options, $preselected ) {
   foreach( $options as $k => $v ) {
     $selected = ( $k == $preselected ) ? ' selected="selected"' : '';
     echo '<option value="', $k, '"', $selected, '>', $v, "</option>\n";
   }
}
/**********  common functions, end                      ************* */
/******************************************************************** */
if( substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' )) { ob_clean(); @ob_start( 'ob_gzhandler' ); } else ob_start();
$title   = 'defs ' . $defsFactory::$version;
if( FALSE !== $logFile )
  $prio  = ( isset( $_REQUEST['lp'] )) ? $_REQUEST['lp'] : LOG_DEBUG;
$invoke  = ( isset( $_REQUEST['invoke'] )) ? $_REQUEST['invoke'] : 0;
$invokes = array( 0 => 'getInstans()',
                  1 => 'factory()',
                );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $title ?> test</title>
<meta name="author"      content="Kjell-Inge Gustafsson - kigkonsult" />
<meta name="copyright"   content="2015 Kjell-Inge Gustafsson - kigkonsult" />
<meta name="keywords"    content="configuration,definitions,locale" />
<meta name="description" content="<?php echo $title ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF8"/>
<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"/>
<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"/>
<style type="text/css">
html {
  cursor          : auto;
}
a {
  text-decoration : none;
  cursor          : pointer;
  cursor          : hand;
}
body {
  font-family     : sans-serif;
  font-size       : 12px;
  width           : 800px;
}
pre, .g {
  background-color:#DCDCDC;
}
table {
  font-family     : sans-serif;
  font-size       : 12px;
  width           : 100%;
}
.bb {
  border-bottom   : thin dotted #dfdfdf;
}
.boxBorder {
  border          : 1px solid #dfdfdf;
  border-radius   : 5px;
  -moz-border-radius : 5px;
  -webkit-border-radius : 5px;
}
.c {
  text-align      : center;
}
.info {
  font-family     : monospace;
  font-size       : 10px;
}
.p {
  font-family     : monospace;
  font-size       : 12px;
}
.r {
  text-align      : right;
}
.t {
  vertical-align  : top;
}
.tc {
  border-collapse: collapse;
}
.wb10 {
  vertical-align  : bottom;
  width           : 10%;
}
.wt10 {
  vertical-align  : top;
  width           : 10%;
}
.wt20 {
  vertical-align  : top;
  width           : 20%;
}
.wt30 {
  vertical-align  : top;
  width           : 30%;
}
.wp {
  white-space     : pre;
}
</style>
</head>
<body>
<a name="top"></a>
<a href="index.php"><h1>defs <?php echo $defsFactory::$version; ?> test</h1></a>
<p>
Some data values are suffixed by the nodeid, to ease up evaluating result.
<br>
All tests may take some seconds, due to PHP 'usleep' command and ttl testing.
</p>
<div class="boxBorder">
<table border=0>
<tr>
<td>
<table>
<tr>
<td>defs invoke</td>
<td>
<select id="invoke"><?php dispOptions( $invokes, $invoke ) ?></select>
</td>
</tr>
<?php if( FALSE !== $logFile ) { ?>
<tr>
<td>log</td>
<td>
<select id="lp"><?php dispOptions( $prios, $prio ) ?></select>
</td>
</tr>
<?php } ?>
</table>
</td>
<td class="r t" colspan="2">&nbsp;<?php if( $includeFileCheck ) include 'lib/fileCheck/fileCheck.php'; ?></td>
</tr>
<tr>
<td class="bb">&nbsp;</td>
<td class="bb" colspan="2">&nbsp;</td>
</tr>
<tr>
<td><i>Press any key to start testing...</i></td>
<td class="c">fetch data from <b>file</b></td>
<td class="c">fetch data from <b>database</b></td>
</tr>
<tr>
<td class="bb c">no setup<br>or null setup</td>
<td class="bb  c" colspan="2"><?php creButton( 'no setup', 'no.setup' ); ?><br><?php creButton( 'null setup', 'null.setup' ); ?></td>
</tr>
<tr>
<td class="bb c">evaluation mode<br><b>no</b> setup array</td>
<td class="bb c"><?php creButton( 'simple', 'simple' ); ?></td>
<td class="bb">&nbsp;</td>
</tr>
<tr>
<td class="bb c"><span>test mode<br>setup using <b>array</b></span></td>
<td class="bb c"><?php creButton( 'setup : array / data : file', 'array.file' ); ?><br><?php creButton( 'setup : array / data : PO file', 'array.PO' ); ?></td>
<td class="bb c"><?php creButton( 'setup : array / data : db', 'array.db' ); ?></td>
</tr>
<tr>
<td class="c" rowspan="2"><span>production mode<br>setup using <b>file</b></span></td>
<td class="bb c"><?php creButton( 'setup : file / data :file', 'file.file' ); ?></td>
<td class="c"><?php creButton( 'setup : file / data :db', 'file.db' ); ?><br><?php creButton( 'setup : file / data :db, 2000 rows', 'file.larger.db' ); ?></td>
</tr>
<tr>
<td class="bb c" colspan="2"><?php creButton( 'setup file / data : file+db+file+db', 'multi.file.db' ); ?></td>
</tr>
<tr>
<td class="bb c">OR all-in-one<br>really take some 40 seconds</td>
<td class="bb  c" colspan="2"><?php creButton( 'all in one', 'allInOne' ); ?></td>
</tr>
</table>
</div>
