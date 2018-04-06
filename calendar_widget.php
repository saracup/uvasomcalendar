<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
function dateToCal($timestamp) {
  return date('Ymd\THis\Z', $timestamp);
}

// Escapes a string of characters
function escapeString($string) {
  return preg_replace('/([\,;])/','\\\$1', $string);
}

?>                        <ul class="calendar">
<?php $counter = 1; ?>
<?php foreach ( $rss_items as $rss_item ): ?>
<?php //$title = $uvasomcalendar->format_title( $rss_item->get_title() ); 
$eventid = uniqid();
$itemurl=($rss_item->get_permalink());
$isuvahealth = stripos($itemurl,'healthsystem.virginia.edu');
if ($isuvahealth >= 1) { $title = ltrim(substr($rss_item->get_title(), 6)," "); }
else { $title=($rss_item->get_title());} ?>
<?php if ( strlen( $title ) < $title_limit ): ?>
<?php if ( $counter == ( $limit + 1 ) ) break; ?>
                                <li id="<?php echo $eventid ?>">
                                        <div class="date">
                                                <span class="day"><?php echo date ( 'd', strtotime( $rss_item->get_date() ) ); ?></span><?php echo date ( 'M', strtotime( $rss_item->get_date() ) ); ?>
                                                
                                        </div>
                                        <div class="description">
                                                <h1><a href="<?php echo $rss_item->get_permalink(); ?>"><?php echo $title; ?></a></h1>
                                                 <p><!--<strong>Time: </strong><?php //echo $rss_item->get_date('g:i a');?><br />-->
												 <?php echo $uvasomcalendar->format_description( $rss_item->get_description(), $desc_limit ); ?></p>

                                        </div>
                                        <!--ical -->
<!--<button id="<?php //echo $eventid ?>" class="uvasomical">ical</button>-->
<!--<div id="<?php //echo $eventid ?>" class="uvasomical">
<?php 
/*$uvasomical = 'BEGIN:VCALENDAR'."\n";
$uvasomical .= 'VERSION:2.0'."\n";
$uvasomical .= 'PRODID:-//hacksw/handcal//NONSGML v1.0//EN'."\n";
$uvasomical .= 'CALSCALE:GREGORIAN'."\n";
$uvasomical .= 'X-WR-TIMEZONE:America/New_York'."\n";
$uvasomical .= 'BEGIN:VTIMEZONE'."\n";
$uvasomical .= 'TZID:America/New_York'."\n";
$uvasomical .= 'X-LIC-LOCATION:America/New_York'."\n";
$uvasomical .= 'BEGIN:DAYLIGHT'."\n";
$uvasomical .= 'TZOFFSETFROM:-0500'."\n";
$uvasomical .= 'TZOFFSETTO:-0400'."\n";
$uvasomical .= 'TZNAME:EDT'."\n";
$uvasomical .= 'DTSTART:19700308T020000'."\n";
$uvasomical .= 'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU'."\n";
$uvasomical .= 'END:DAYLIGHT'."\n";
$uvasomical .= 'BEGIN:STANDARD'."\n";
$uvasomical .= 'TZOFFSETFROM:-0400'."\n";
$uvasomical .= 'TZOFFSETTO:-0500'."\n";
$uvasomical .= 'TZNAME:EST'."\n";
$uvasomical .= 'DTSTART:19701101T020000'."\n";
$uvasomical .= 'RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU'."\n";
$uvasomical .= 'END:STANDARD'."\n";
$uvasomical .= 'END:VTIMEZONE'."\n";

$uvasomical .= 'BEGIN:VEVENT'."\n";
//$uvasomical .= 'DTEND:'.date ( 'Ymd\THis\Z', strtotime( $rss_item->get_date() ) )."\n";
$uvasomical .= 'UID:'.$eventid.'-'.md5(mt_rand()).'-'.(($eventid + rand())*2).'@med.virginia.edu'."\n";
$uvasomical .= 'DTSTAMP:'.dateToCal(time())."\n";
$uvasomical .= 'DESCRIPTION:'.escapeString($rss_item->get_description())."\n";
$uvasomical .= 'URL;VALUE=URI:'.escapeString($rss_item->get_permalink())."\n";
$uvasomical .= 'SUMMARY:'.$rss_item->get_title()."\n";
$uvasomical .= 'DTSTART:'.date ( 'Ymd\THis\Z', strtotime( $rss_item->get_date() ) )."\n";
$uvasomical .= 'END:VEVENT'."\n";
$uvasomical .= 'END:VCALENDAR'."\n";

echo $uvasomical;*/
?>
</div>-->

                                </li>
<?php $counter++; ?>
<?php endif; ?>
<?php endforeach; ?>
                        </ul>