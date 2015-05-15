<?php
include_once('most_recent_date.php') ;
include_once('functions.php') ;
include_once('comments.php') ;
$title = 'Project 365: A photo a day' ;
$stylesheets = array('style.css') ;
$js_scripts  = array('comments_js.php', 'functions.js') ;
include_once($_SERVER['FILE_PREFIX'] . '/_core/preamble.php') ;

date_default_timezone_set('Europe/London') ;
$year  = date('Y') ;
$month = date('m') ;
$n_months_to_show = 6 ;
if(isset($_GET['nMonths'])){ $n_months_to_show = 0+$_GET['nMonths'] ; }
if(isset($_GET['year'   ])){ $year             = 0+$_GET['year'   ] ; $month = 12 ; }
if(isset($_GET['month'  ])){ $month            = 0+$_GET['month'  ] ; }
if( $year<2012 || $year>$most_recent_year) $year  = date('Y') ;
if($month<1    || $month>12              ) $month = date('m') ;

// $year_in etc are variables that take any $_GET requests into account
// $year etc are variables that change as we add more months to the list of months
$year_in  = $most_recent_year  ;
$month_in = $most_recent_month ;
$day_in   = ($year_in==$most_recent_year && $month_in==$most_recent_month) ? $most_recent_day : cal_days_in_month(0,$month_in,$year_in) ;

$the_months = array() ;
while($year>2011){
  $add_month = true ;
  if($year>$most_recent_year) $add_month = false ;
  if($year==$most_recent_year && $month>$most_recent_month) $add_month = false ;
  
  if($add_month) $the_months[] = new month_object($year,$month) ;
  $month-- ;
  if($month<1){
    $month = 12 ;
    $year-- ;
  }
}

$start = 0 ;
for($i=0 ; $i<count($the_months) ; $i++){
  $m = $the_months[$i] ;
  if($m->year==$year && $m->month==$month){
    $start = $i ;
    break ;
  }
}
$early_index = count($the_months)-$i+$n_months_to_show-1 ;
$early_month_object = $the_months[$early_index] ;
$early_month = $early_month_object->month ;
$early_year  = $early_month_object->year  ;

if($month_in<10) $month_in = '0' . $month_in ;
if($day_in  <10) $day_in   = '0' . $day_in   ;
$current_date = $year_in . '/' . $month_in . '/' . $day_in ;
$current_comment = $comments[ $current_date ] ;

echo '<script>' , PHP_EOL ;
echo 'var most_recent_year  = ' , $most_recent_year  , ' ;' , PHP_EOL ;
echo 'var most_recent_month = ' , $most_recent_month , ' ;' , PHP_EOL ;
echo 'var most_recent_day   = ' , $most_recent_day   , ' ;' , PHP_EOL ;
echo 'var early_year        = ' , $early_year        , ' ;' , PHP_EOL ;
echo 'var early_month       = ' , $early_month       , ' ;' , PHP_EOL ;
echo 'var GET_year          = ' , $year_in           , ' ;' , PHP_EOL ;
echo 'var GET_month         = ' , $month_in          , ' ;' , PHP_EOL ;
echo 'var GET_nMonths       = ' , $n_months_to_show  , ' ;' , PHP_EOL ;
echo '</script>' , PHP_EOL ;
?>
<div class="right">
  <div class="blurb">
  <form action="index.php" method="GET">
    <p class="center">Show photos for
      <select name="month">
        <option value="1">January</option>
        <option value="2">February</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
      </select>
      <select name="year">
        <option value="2012">2012</option>
        <option value="2013">2013</option>
        <option value="2014">2014</option>
      </select>
      and show
      <select id="select_nMonths" name="nMonths">
        <option value="1">1</option>
      </select>
      months. 
      <input type="submit" value="Go!" />
    </p>
  </form>
  </div>
</div>

<div id="large_wrapper">
  <img id="large_image" src="images/<?=$year_in;?>/<?=$month_in;?>/midsize/<?=$year_in;?><?=$month_in;?><?=$day_in;?>.jpg"/>
  <p id="p_caption"><span id="image_date"><?php echo $current_date ;?></span>: <span id="image_caption"><?php echo $current_comment ; ?></span></p>
</div>

<table id="table_photos">
  <tbody>
<?php
for($i=0 ; $i<$n_months_to_show ; $i++){
  echo $the_months[$i]->trs_HTML() ;
}
?>
  </tbody>
</table>
<?php foot() ; ?>
