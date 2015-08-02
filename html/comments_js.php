<?php

ini_set('display_startup_errors',1) ;
ini_set('display_errors',1) ;
error_reporting(-1) ;

include_once('functions.php') ;
include_once('comments.php') ;
echo 'var c = new Array() ;' , PHP_EOL ;
$keys = array_keys($comments) ;
foreach($keys as $k){
  echo 'c["' , $k , '"]="' , $comments[$k] , '";' , PHP_EOL ;
}
echo 'var comments = c ;' , PHP_EOL ;

?>