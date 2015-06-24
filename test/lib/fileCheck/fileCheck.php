<?php
/**
 * check and show status (size) of (log) files
 *
 * offers (display+) upload     (read rights required)
 *        emptying of the files (writes rights required, opt)
 *
 * @package    fileCheck
 * @copyright  Copyright (c) 2015 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @author     Kjell-Inge Gustafsson <ical@kigkonsult.se>
 * @link       http://kigkonsult.se/defs/index.php
 * @license    non-commercial use: Creative Commons
 *             Attribution-NonCommercial-NoDerivatives 4.0 International License
 *             (http://creativecommons.org/licenses/by-nc-nd/4.0/)
 * @version    1.0
 *
 *  fileCheck.php
 */
require 'fileCheck.cfg.php';
?>
<fieldset id="fCBox">
<legend align="<?php echo $fClegendAlign; ?>"><?php echo $fClabels['legend']; ?>&nbsp;<a onClick="fCtoogle();">[+/-]</a></legend>
<table id="fCrBox" style="display:none;"></table>
<script type="text/javascript">
var url           = '<?php echo $fCurl; ?>',
    txtEmpty      = '<?php echo $fClabels['makeEmpty']; ?>',
    txtEmptyTitle = '<?php echo $fClabels['makeEmptyTitle']; ?> ',
    txtUpl        = '<?php echo $fClabels['upload']; ?>',
    txtUplTitle   = '<?php echo $fClabels['uploadTitle']; ?> ',
    fCBox         = document.getElementById('fCBox');
<?php foreach( $fCboxStyles as $style => $value ) { ?>
fCBox.style.<?php echo "{$style} = '{$value}'"; ?>;
<?php } ?>
</script>
<script type="text/javascript" src="<?php echo $fCjsUrl; ?>"></script>
</fieldset>
