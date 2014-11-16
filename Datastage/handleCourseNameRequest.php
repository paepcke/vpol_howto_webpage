<!DOCTYPE html>
<html>
  <head>
    <title>Course List</title>
    <meta charset="UTF-8"> 
    <style>
      body
      {
      background-color:#b0c4de;
      }
      #spanhand
      {
      font-style: bold;
      font-size: 150%;
      display: inline-block;
      }
    </style>
  </head>
  <body>
<?php 
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
