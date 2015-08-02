<?php
$nCols = 7 ;

function  image_path($y,$m,$d){ return sprintf('images/%04d/%02d/%04d%02d%02d.jpg', $y, $m, $y, $m, $d) ; }
function  thumb_path($y,$m,$d){ return sprintf('images/%04d/%02d/thumbnails/%04d%02d%02d.jpg', $y, $m, $y, $m, $d) ; }
function date_string($y,$m,$d){ return sprintf('%04d/%02d/%02d', $y, $m, $d) ; }

class day_object{
  function __construct($year, $month, $day, $type){
    global $comments, $most_recent_year, $most_recent_month, $most_recent_day ;
    $this->year    = $year    ;
    $this->month   = $month   ;
    $this->day     = $day     ;
    $this->type    = $type    ;
    
    $this->is_valid = true ;
    if($this->year>$most_recent_year) $this->is_valid = false ;
    if($this->year==$most_recent_year && $this->month>$most_recent_month) $this->is_valid = false ;
    if($this->year==$most_recent_year && $this->month==$most_recent_month && $this->day>$most_recent_day) $this->is_valid = false ;
    if($this->is_valid==false){
      $this->year    = 0 ;
      $this->month   = 0 ;
      $this->day     = 0 ;
      $this->type    = 0 ;
    }
        
    $this->image_url = image_path($this->year,$this->month,$this->day) ;
    $this->thumb_url = thumb_path($this->year,$this->month,$this->day) ;
    $this->date_string = date_string($this->year,$this->month,$this->day) ;
    $this->id_string = sprintf('%04d%02d%02d', $this->year, $this->month, $this->day) ;
    $this->comment = isset($comments[$this->date_string]) ? $this->comment = $comments[$this->date_string] : '' ;
  }
  function td_HTML(){
    $prefix = '          ' ;
    $string = array() ;
    $string[] = $prefix . '<td class="td_photo">' ;
    $string[] = '  <div class="div_photo">' ;
    if($this->year>0){
      $string[] = $this->date_string ;
      $string[] = '    <br />' ;
      //$string[] = '    <a href="' . $this->image_url . '">' ;
      $string[] = '    <img id="img_' . $this->id_string . '" class="photo" src="' . $this->thumb_url . '" width="80px" height="80px" alt="' . $this->comment . '" title="' . $this->comment . '" />' ;
      //$string[] = '    </a>' ;
    }
    else{
      $string[] = '' ;
      $string[] = '    <br />' ;
      $string[] = '      <img id="img_' . $this->date_string . '" class="blank_photo" src="' . $this->thumb_url . '" width="80px" height="80px" alt="' . $this->comment . '" title="' . $this->comment . '" />' ;
    }
    $string[] = '  </div>' ;
    $string[] = '</td>' ;
    return implode(PHP_EOL.$prefix,$string) ;
  }
}

class week_object{
  public $days = array() ;
  public $is_empty = true ;
  function add_day($day){
    $this->days[] = $day ;
    if($day->year>0) $this->is_empty = false ;
  }
  function get_day_by_name($name){
    for($i=0 ; $i<count($this->days) ; $i++){
      if($this->days[$i].name==$name) return $this->days[$i] ;
    }
    return blank_day() ;
  }
  function tr_HTML(){
    $prefix = '        ' ;
    $string = array() ;
    $string[] = $prefix . '<tr>' ;
    for($i=0 ; $i<count($this->days) ; $i++){
      $string[] = $this->days[$i]->td_HTML() ;
    }
    $string[] = $prefix . '</tr>' ;
    return implode(PHP_EOL,$string) ;
  }
}

function blank_day(){ return new day_object(0,0,0,'',0) ; }

class month_object{
  public $days = array() ;
  function populate_days(){
    for($i=1 ; $i<=$this->nDays ; $i++){
      $string = date_string($this->year,$this->month,$i) ;
      $day = new day_object($this->year, $this->month, $i, 1) ;
      $this->days[] = $day ;
    }
  }
  function populate_weeks(){
    $week = new week_object() ;
    $counter = 0 ;
    for($i=0 ; $i<$this->nDaysBefore ; $i++){
      $week->add_day(blank_day()) ;
      $counter++ ;
    }
    for($i=0 ; $i<count($this->days) ; $i++){
      if($counter%7==0){
        $this->weeks[] = $week ;
        $week = new week_object() ;
      }
      $week->add_day($this->days[$i]) ;
      $counter++ ;
    }
    for($i=0 ; $i<$this->nDaysAfter ; $i++){
      $week->add_day(blank_day()) ;
      $counter++ ;
    }
    if(count($week)>0) $this->weeks[] = $week ;
  }
  function trs_HTML(){
    $prefix = '          ' ;
    $string = array() ;
    $string[] = PHP_EOL . $prefix . '<tr><th class="month_heading" colspan="7">' . $this->month_name . ' ' . $this->year_name . '</th></tr>' ;
    $string[] = '<tr>' ;
    $string[] = '  <th class="day_name">Sunday</th>'    ;
    $string[] = '  <th class="day_name">Monday</th>'    ;
    $string[] = '  <th class="day_name">Tuesday</th>'   ;
    $string[] = '  <th class="day_name">Wednesday</th>' ;
    $string[] = '  <th class="day_name">Thursday</th>'  ;
    $string[] = '  <th class="day_name">Friday</th>'    ;
    $string[] = '  <th class="day_name">Saturday</th>'  ;
    $string[] = '</tr>' ;
    for($i=count($this->weeks)-1 ; $i>=0 ; $i--){
      if($this->weeks[$i]->is_empty==true) continue ;
      $string[] = $this->weeks[$i]->tr_HTML() ;
    }
    return implode(PHP_EOL.$prefix,$string) ;
  }
  function __construct($year, $month){
    $this->year  = $year  ;
    $this->month = $month ;
    $this->weeks = array() ;
    $this->nDays       = cal_days_in_month(0,$this->month,$this->year) ;
    $this->nDaysBefore = intval(date('w', strtotime( date_string($this->year,$this->month,1) ) ) ) ;
    $this->nWeeks      = ceil(($this->nDaysBefore+$this->nDays)/7) ;
    $this->nDaysAfter  = 7*$this->nWeeks - ($this->nDays+$this->nDaysBefore) ;
    $this->month_name = date('F', strtotime(date_string($this->year,$this->month,1))) ;
    $this->year_name  = date('Y', strtotime(date_string($this->year,$this->month,1))) ;
    
    $this->populate_days() ;
    $this->populate_weeks() ;
  }
}

function increment_day($year,$month,$day){
  $day++ ;
  if($day>cal_days_in_month(0,$month,$year)){
    $day = 1 ;
    $month++ ;
    if($month>12){
      $month = 1 ;
      $year++ ;
    }
  }
  $result = array() ;
  $result[0] = $year  ;
  $result[1] = $month ;
  $result[2] = $day   ;
  return $result ;
}
?>