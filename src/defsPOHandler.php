<?php
/*********************************************************************************/
/**
 *
 * defs, a PHP key-value definition handle class package, managing keyed data
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
 */
/*********************************************************************************/
/**
 * defsPOHandler class
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @link   http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files
 * @since  1.0 - 2015-06-20
 */
class defsPOHandler extends defsFileHandler implements defsSourceInterface {
/**
 * @var array $logtxt    log texts
 * @access protected
 * @static
 */
  protected static $logtxt  = array( "%d, The PO file '%s' contains an error: '%s' was expected but not found on line %d, msgid no %d", // 0+1
                                     "%d, The PO file '%s' contains a syntax error on line %d, msgid no %d",               // 2+4+6+7+9+10
                                     "%d, The PO file '%s' contains an error: '%s' is unexpected on line %d, msgid no %d", // 3+5+8
                                     "%d, The PO file '%s' contains an unknown string on line %d, %s'%s'",                 // 11
                                     "%d, The PO file '%s' ended unexpectedly at line %d",                                 // 12
                                   );
/**
 * @var array $POheaders
 * @access private
 * @static
 */
  private static $POheaders = array( 'Project-Id-Version',
                                     'Report-Msgid-Bugs-To',
                                     'POT-Creation-Date',
                                     'PO-Revision-Date',
                                     'Last-Translator',
                                     'Language-Team',
                                     'Language',
                                     'MIME-Version',
                                     'Content-Type',
                                     'Content-Transfer-Encoding',
                                     'Plural-Forms',
                                     'X-',
                                   );

/**
 * fetch source values from (a valid) PO file
 *
 * the return array base key (nodeid) is fetched from  $source['nodeid']
 * skip all but msgid/msgstr entries
 *
 * @param array   $source
 * @uses defsFileHandler::$logtxt
 * @uses defsPOHandler::$logtxt
 * @uses defsPOHandler::unQuote()
 * @uses defsPOHandler::$POheaders
 * @uses defsFileHandler::fileClosure()
 * @throws file read/syntax exception
 * @static
 * @return array
 */
  public static function loadData( array $source ) {
    $start     = microtime( TRUE );
    static $COMMENT      = 'comment';
    static $MSGCTXT      = 'msgctxt';
    static $MSGID        = 'msgid';
    static $MSGID_PLURAL = 'msgid_plural';
    static $MSGSTR       = 'msgstr';
    static $MSGSTR_ARR   = 'msgstr_arr';
    static $cmtChar      = '#';
    static $colonChar    = ':';
    $prevKey   = $COMMENT;  // previous entry: COMMENT, MSGID, MSGID_PLURAL, MSGSTR and MSGSTR_ARR
    $curData   = array();   // Current entry being read
    $msgidCnt  = 0;
    $msgIX     = 0;         // Current plural index
    $rowNo     = 0;         // Current line number
    $file      = $source['path'];
    $output    = array();
    if( FALSE === ( $rows = file( $file, FILE_IGNORE_NEW_LINES )))
      throw new Exception( sprintf( parent::$logtxt[0], $file ));
    foreach( $rows as $line ) {
      if( 0 == $rowNo )     // The first line might come with a UTF-8 BOM, which should be removed.
        $line  = str_replace("\xEF\xBB\xBF", '', $line);
      $rowNo  += 1;
      switch( TRUE ) {
        case ( ! strncmp( $cmtChar, $line, 1 )) :  // msg comment
        case ( ! strncmp( $MSGCTXT, $line, 7 )) :  // msg context
          if(( $MSGSTR == $prevKey ) || ( $MSGSTR_ARR == $prevKey )) { // End current entry, start a new one
            if( ! empty( $curData[$MSGID] )) {
              if( is_array( $curData[$MSGID] )) {
                foreach( $curData[$MSGID] as $m )
                  $output[$m] = $curData[$MSGSTR];
              }
              else
                $output[$curData[$MSGID]] = $curData[$MSGSTR];
            }
            $curData = array( $MSGID => '', $MSGSTR => '' );
            $prevKey = $COMMENT;
          }
          elseif( $COMMENT != $prevKey )  // Parse error
            throw new Exception( sprintf( defsPOHandler::$logtxt[0], 480, $file, $MSGSTR, $rowNo, $msgidCnt ));
          continue;
        case ( ! strncmp( $MSGID_PLURAL, $line, 12 )) :
          if( $MSGID != $prevKey )        // Must be plural form for current entry
            throw new Exception( sprintf( defsPOHandler::$logtxt[0], 481, $file, $MSGID_PLURAL, $rowNo, $msgidCnt ));
          if( FALSE !== ( $quoted = defsPOHandler::unQuote( $line, 12 ))) {
            if( ! is_array( $curData[$MSGID] ))
              $curData[$MSGID] = array( $curData[$MSGID] );
            $curData[$MSGID][] = $quoted;
            $prevKey           = $MSGID_PLURAL;
          }
          else
            throw new Exception( sprintf( defsPOHandler::$logtxt[1], 482, $file, $rowNo, $rowNo, $msgidCnt ));
          continue;
        case ( ! strncmp( $MSGID, $line, 5 )) :  // msgid
          if( $MSGSTR == $prevKey )       // End current entry, start a new one
            $curData = array( $MSGID => '', $MSGSTR => '' );
          elseif( $MSGID == $prevKey )    // Already in this context? Parse error
            throw new Exception( sprintf( defsPOHandler::$logtxt[2], 483, $file, $MSGID, $rowNo, $msgidCnt ));
          if( FALSE === ( $quoted = defsPOHandler::unQuote( $line, 5 )))
            throw new Exception( sprintf( defsPOHandler::$logtxt[1], 484, $file, $rowNo, $msgidCnt ));
          if( empty( $msgidCnt ) && empty( $quoted )) // skip first empty msgid
            continue;
          $msgidCnt += 1;
          $curData[$MSGID] = $quoted;
          $prevKey         = $MSGID;
          continue;
        case ( ! strncmp( $MSGSTR . '[', $line, 7 )) :  // msg string, plural
          if (( $MSGID != $prevKey ) && ( $MSGID_PLURAL != $prevKey ) && ( $MSGSTR_ARR != $prevKey )) // Must come after msgid, msgid_plural, or msgstr[]
            throw new Exception( sprintf( defsPOHandler::$logtxt[2], 485, $file, $MSGSTR . '[]', $rowNo, $msgidCnt ));
          if( FALSE === strpos( $line, ']' ))
            throw new Exception( sprintf( defsPOHandler::$logtxt[1], 486, $file, $rowNo, $msgidCnt ));
          $startIx = strstr( $line, '[' );
          $msgIX   = substr( $startIx, 1, strpos( $startIx, ']' ) - 1 );
          $line    = trim( strstr( $line, ' ' ));
          if( FALSE === ( $quoted = defsPOHandler::unQuote( $line )))
            throw new Exception( sprintf( defsPOHandler::$logtxt[1], 487, $file, $rowNo, $msgidCnt ));
          if( empty( $quoted )) {
            if( is_array( $curData[$MSGID] ))
              $quoted  = ( isset( $curData[$MSGID][$msgIX] )) ? $curData[$MSGID][$msgIX] : end( $curData[$MSGID] );
            else
              $quoted  = $curData[$MSGID];
          }
          $curData[$MSGSTR][$msgIX] = $quoted;
          $prevKey                   = $MSGSTR_ARR;
          continue;
        case ( ! strncmp( $MSGSTR, $line, 6 )) :   // msg string
          if(( $MSGID != $prevKey ) && ! empty( $msgidCnt )) // Should come just after a msgid block
            throw new Exception( sprintf( defsPOHandler::$logtxt[2], 488, $file, $MSGSTR, $rowNo, $msgidCnt ));
          if( FALSE === ( $quoted = defsPOHandler::unQuote( $line, 6 )))
            throw new Exception( sprintf( defsPOHandler::$logtxt[1], 489, $file, $rowNo, $msgidCnt ));
          if( empty( $quoted )) {
            if( empty( $msgidCnt )) // skip first empty msgstr
              continue;
            $quoted  = $curData[$MSGID];
          }
          $curData[$MSGSTR] = $quoted;
          $prevKey          = $MSGSTR;
          continue;
        case ( '' != $line ) :           // managing multi-line string
          if( FALSE === ( $quoted = defsPOHandler::unQuote( $line )))
            throw new Exception( sprintf( defsPOHandler::$logtxt[1], 490, $file, $rowNo, $msgidCnt ));
          switch( $prevKey ) {
            case $MSGID:
            case $MSGID_PLURAL:
              $curData[$MSGID]           .= $quoted;
              break;
            case $MSGSTR:
              $curData[$MSGSTR]          .= $quoted;
              break;
            case $MSGSTR_ARR:
              $curData[$MSGSTR][$msgIX] .= $quoted;
              break;
            default:
              if(( FALSE === ( $pos = strpos( $quoted, $colonChar ))) ||  // msg headers...
                 ( ! in_array( substr( $quoted, 0, $pos ), defsPOHandler::$POheaders ) && ( 'X-' != substr( $quoted, 0, 2 ))))
                throw new Exception( sprintf( defsPOHandler::$logtxt[3], 491, $file, $rowNo, PHP_EOL, $quoted ));
              break;
          } // end switch( $prevKey )
          continue;
      } // edn switch( TRUE )
    } // end foreach( $rows as $line )
    unset( $rows );
    switch( $prevKey ) { // End of PO file, flush last entry
      case $MSGSTR:
      case $MSGSTR_ARR:
        if( ! empty( $curData[$MSGID] )) {
          if( is_array( $curData[$MSGID] )) {
            foreach( $curData[$MSGID] as $m )
              $output[$m] = $curData[$MSGSTR];
          }
          else
            $output[$curData[$MSGID]] = $curData[$MSGSTR];
        }
        break;
      case $COMMENT:
        break;
      default:
        throw new Exception( sprintf( defsPOHandler::$logtxt[4], 420, $file, $rowNo ));
        break;
    }
    return parent::fileClosure( array( $source['nodeid'] => $output ), $source, $start );
  }
/**
* Parses a quoted string
*
* @param $string A string specified with enclosing quotes
* @param $substrIx
* @return The string parsed from inside the quotes
*/
  private static function unQuote( $string, $substrIx=null ) {
    static $dq = '"';
    static $sq = "'";
    if( ! empty( $substrIx ))
      $string  = trim( substr( $string, $substrIx ));
    if( substr( $string, 0, 1 ) != substr( $string, -1, 1 ))
      return FALSE;   // Start and end quotes must be the same
    $qoute     = substr( $string, 0, 1 );
    $string    = substr($string, 1, -1);
    if( empty( $string ))
      return '';
    switch( $qoute ) {
      case $dq:       // Double quotes: strip slashes
        return stripcslashes($string);
        break;
      case $sq:       // Simple quote: return as-is
        return $string;
        break;
      default:
        return FALSE; // Unrecognized quote
    }
  }
}
