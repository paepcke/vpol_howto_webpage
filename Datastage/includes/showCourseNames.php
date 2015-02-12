<?php
//*******
//phpinfo();
//error_reporting(E_ALL);
//*******

function printCourseNames($platform) {
    if ($platform != 'coursera' &&
        $platform != 'openedx' &&
        $platform != 'moocdb' &&
        $platform != 'novoed')
        die("Only courses for platforms coursera, openedx, and moocdb are available on datastage.stanford.edu");
    prepDb();
    if ($platform == 'coursera') {
        // SQL query
        $strSQL = "SELECT DISTINCT extractCourseraCourseName(SCHEMA_NAME) AS courseName
                  FROM information_schema.SCHEMATA WHERE schema_name
                  LIKE 'Coursera%' ORDER BY courseName;";
        echo "<b>Coursera courses archived on Datastage</b><br>";
    } elseif ($platform == 'moocdb') {
        $strSQL = "SELECT DISTINCT extractMoocDbCourseName(SCHEMA_NAME) AS courseName
                  FROM information_schema.SCHEMATA 
                  WHERE isMoocDbCourseName(schema_name) != '' ORDER BY courseName;";
        echo "<b>MOOCDb courses archived on Datastage</b><br>";
    } elseif ($platform == 'openedx') {
        $strSQL = "SELECT course_display_name AS courseName, academic_year, quarter
                   FROM CourseInfo ORDER BY courseName;";
        echo "<b>OpenEdX courses archived on Datastage</b><br>";
    } elseif ($platform == 'novoed') {
        $strSQL = "SELECT DISTINCT extractNovoEdCourseName(SCHEMA_NAME) AS courseName
                   FROM information_schema.SCHEMATA 
                   WHERE schema_name LIKE 'novoed%' ORDER BY courseName;";
        echo "<b>NovoEd courses archived on Datastage</b><br>";
    }

    // Execute the query (the recordset $result contains the result
    // an a php array):
    $result = mysql_query($strSQL) or die (mysql_error());

    // Print the array to the browser (debugging only):
    // while ($row = mysql_fetch_array($result)) {
    //     foreach ($row as $columnName => $columnData) {
    //         echo 'Column name: ' . $columnName . ' Column data: ' . $columnData . '<br />';
    //     }
    // }
    
    // Loop the recordset $result
    // Each row will be made into an array ($row) using mysql_fetch_array
    while($row = mysql_fetch_assoc($result)) {
      if ($platform != 'openedx') {
        // Write the value back to the browser:
        echo $row['courseName'] . "<br />";
      } else {

          //*********
          //echo "academic_year: " . $row['academic_year'] . '\n';
          //echo "quarter: " . $row['quarter'] . '\n';
          //*********
        
          if ($row['academic_year'] > 2014 ||
              ($row['academic_year'] == 2014 && $row['quarter'] == 'summer')) {
              echo '<span class="sharable">' . $row['courseName'] . '</span>' . '<br />';
          } else {
              echo $row['courseName'] . "<br />";
          }
      }
    }

   // Close the database connection
    mysql_close();
}

function prepDb() {
    // Connect to database server. The mysqli.default.xxx
    // variables below are set in /etc/php5/apache2/php.ini:
    //****mysql_connect(get_cfg_var("mysqli.default_host"), get_cfg_var("mysqli.default_user"), get_cfg_var("mysqli.default_pw"));
    //****mysql_connect(get_cfg_var("mysqli.default_host"), "dataman", file_get_contents("/var/www/include/mysql"));
    mysql_connect("localhost", "webnobody", "Rpd$9Q34@?");
    // Select database
    mysql_select_db("Edx") or die(mysql_error());
}

?>
