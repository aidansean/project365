<?php
include_once($_SERVER['FILE_PREFIX']."/project_list/project_object.php") ;
$github_uri   = "https://github.com/aidansean/project365" ;
$blogpost_uri = "http://aidansean.com/projects/?tag=project365" ;
$project = new project_object("project365", "Project 365", "https://github.com/aidansean/project365", "http://aidansean.com/projects/?tag=project365", "365/images/project.jpg", "365/images/project_bw.jpg", "I keep a project 365 page, which means one photo per day.  It's a good way to keep track of the passage of time and as I take my camera everywhere with me it' quite easy.  After using a blog for the photos I soon became frustrated at how long it took, so I decided to make my own wrapper for the images.", "", "") ;
?>