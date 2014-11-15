<!DOCTYPE html>
<html>
  <head>
    <title>Coursera Courses</title>
    <meta charset="UTF-8"> 
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
