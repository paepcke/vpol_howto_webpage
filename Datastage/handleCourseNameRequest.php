<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Course List</title>
    <style>
      body
      {
      background-color:#b0c4de;
      }
      #spanhand
      {
      font-weight: bold;
      font-size: 150%;
      display: inline-block;
      }
      span.sharable {color: green;}
    </style>
  </head>
  <body>

<?php 

$docRoot = $_SERVER['DOCUMENT_ROOT'];

require($_SERVER['DOCUMENT_ROOT']."/includes/showCourseNames.php");

if (! isset($_GET["platform"])) {
    die("In call to handleCourseNameRequest.php: URL needs a 'platform' parameter.");
} else {
    $platform = $_GET["platform"];
}

printCourseNames($platform); 

?>

</body>
</html>
