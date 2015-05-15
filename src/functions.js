var current_y = -1 ;
var current_m = -1 ;
var current_d = -1 ;
function start(){
  var current_y = most_recent_year  ;
  var current_m = most_recent_month ;
  var current_d = most_recent_day   ;
  var y = most_recent_year  ;
  var m = most_recent_month ;
  var d = most_recent_day   ;
  if(GET_year<y){
    y = GET_year ;
    m = GET_month ;
    d = 1 ;
  }
  else if(GET_month<m){
    m = GET_month ;
    d = 1 ;
  }
  if(y<=0) y = '0000' ;
  if(m<10) m = '0' + m ;
  if(d<10) d = '0' + d ;
  var current_y = y ;
  var current_m = m ;
  var current_d = d ;
  var nMonths_in = GET_nMonths ;
  var url = 'images/'+y+'/'+m+'/midsize/'+(y)+(m)+(d)+'.jpg' ;
  Get('large_image').src = url ;
  Get('image_caption').innerHTML = get_comment(y,m,d) ;
  Get('image_date'   ).innerHTML = y + '/' + m + '/' + d ;
  
  document.addEventListener("keydown", keyDown) ;
  add_eventListeners() ;
  
  var select = Get('select_nMonths') ;
  for(var i=2 ; i<=36 ; ++i){
    var option = document.createElement('option') ;
    option.value = i ;
    option.innerHTML = i ;
    select.appendChild(option) ;
  }
}
function keyDown(evt){
  var keyDownID = window.event ? event.keyCode : (evt.keyCode != 0 ? evt.keyCode : evt.which) ;
  switch(keyDownID){
    case 37: evt.preventDefault() ; decrease_day()   ; break ; // left
    case 38: evt.preventDefault() ; increase_month() ; break ; // up
    case 39: evt.preventDefault() ; increase_day()   ; break ; // right
    case 40: evt.preventDefault() ; decrease_month() ; break ; // down
  }
}

function decrease_month(){
  if(current_y==early_year && current_m==early_month) return ;
  if(current_m==1){
    current_m = 12 ;
    current_y-- ;
  }
  else{
    current_m-- ;
  }
  current_d = (current_d>days_in_month(current_y,current_m)) ? days_in_month(current_y,current_m) : current_d ;
  var y = current_y ;
  var m = (current_m<10) ? '0'+current_m : current_m ;
  var d = (current_d<10) ? '0'+current_d : current_d ;
  change_image_ymd(y,m,d) ;
}
function increase_month(){
  if(current_y==most_recent_year && current_m==most_recent_month){
    current_d = most_recent_day ;
  }
  else if(current_m==12){
    current_m = 1 ;
    current_y++ ;
  }
  else{
    current_m++ ;
  }
  current_d = (current_d>days_in_month(current_y,current_m)) ? days_in_month(current_y,current_m) : current_d ;
  var y = current_y ;
  var m = (current_m<10) ? '0'+current_m : current_m ;
  var d = (current_d<10) ? '0'+current_d : current_d ;
  change_image_ymd(y,m,d) ;
}
function decrease_day(){
  if(current_y==early_year && current_m==early_month && current_d==1) return ;
  if(current_d==1){
    if(current_m==1){
      current_m = 12 ;
      current_y-- ;
      current_day = 31 ;
    }
    else{
      current_m-- ;
      current_d = days_in_month(current_y,current_m) ;
    }
  }
  else{
    current_d-- ;
  }
  var y = current_y ;
  var m = (current_m<10) ? '0'+current_m : current_m ;
  var d = (current_d<10) ? '0'+current_d : current_d ;
  change_image_ymd(y,m,d) ;
}
function increase_day(){
  if(current_d==most_recent_day && current_m==most_recent_month && current_y==most_recent_year) return ;
  var days_this_month = days_in_month(current_y,current_m) ;
  if(current_d<days_this_month){
    current_d++ ;
  }
  else{
    current_d = 1 ;
    if(current_m==12){
      current_m = 1 ;
      current_y++ ;
    }
    else{
      current_m++ ;
    }
  }
  var y = current_y ;
  var m = (current_m<10) ? '0'+current_m : current_m ;
  var d = (current_d<10) ? '0'+current_d : current_d ;
  change_image_ymd(y,m,d) ;
}
function days_in_month(y,m){
  switch(m){
    case  1:
    case  3:
    case  5:
	case  7:
	case  8:
	case 10:
	case 12:
	  return 31 ;
	  break ;
	case  4:
	case  6:
	case  9:
	case 11:
	  return 30 ;
	  break ;
	case 2:
	  return (y%4==0) ? 29 : 28 ;
	  break ;
  }
}

function change_image(evt){
  var target ;
  if(!evt) var evt = window.event ;
  if(evt.target) target = evt.target;
  else if(evt.srcElement) target = evt.srcElement ;
  if(target.nodeType==3) target = target.parentNode ; // defeat Safari bug
  var id = target.id ;
  var y = id.substring( 4, 8) ;
  var m = id.substring( 8,10) ;
  var d = id.substring(10,12) ;
  current_y = parseInt(y) ;
  current_m = parseInt(m) ;
  current_d = parseInt(d) ;
  change_image_ymd(y,m,d) ;
}
function change_image_ymd(y,m,d){
  var img = Get('large_image') ;
  img.style.opacity = 0.5 ;
  var url = 'images/'+y+'/'+m+'/midsize/'+(y)+(m)+(d)+'.jpg' ;
  img.onload = function(){ Get('large_image').style.opacity = 1.0 ; } ;
  img.src = url ;
  Get('image_caption').innerHTML = get_comment(y,m,d) ;
  Get('image_date'   ).innerHTML = y + '/' + m + '/' + d ;
}
function add_eventListeners(){
  var imgs = document.getElementsByTagName('img') ;
  for(var i=0 ; i<imgs.length ; ++i){
    if(imgs[i].className=='photo'){
      imgs[i].addEventListener("click",change_image) ;
    }
  }
}
function get_comment(y,m,d){
  var key = y+'/'+m+'/'+d ;
  var result = comments[key] ;
  if(result==undefined) return 'No comment...' ;
  return result ;
}

function Get(id){ return document.getElementById(id) ; }
