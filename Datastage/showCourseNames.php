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
                  FROM information_schema.SCHEMATA 
                  WHERE schema_name LIKE 'Coursera%' ORDER BY courseName;";
        echo "<b>Coursera courses archived on Datastage</b><br>";
    } elseif ($platform == 'moocdb') {
        $strSQL = "SELECT DISTINCT extractMoocDbCourseName(SCHEMA_NAME) AS courseName
                  FROM information_schema.SCHEMATA 
                  WHERE isMoocDbCourseName(schema_name) != '' ORDER BY courseName;";
        echo "<b>MOOCDb courses archived on Datastage</b><br>";
    } elseif ($platform == 'openedx') {
        $strSQL = "SELECT course_display_name AS courseName
                   FROM CourseInfo ORDER BY courseName;";
        echo "<b>OpenEdX courses archived on Datastage</b><br>";
    } elseif ($platform == 'novoed') {
        $strSQL = "SELECT DISTINCT extractNovoEdCourseName(SCHEMA_NAME) AS courseName
                   FROM information_schema.SCHEMATA 
                   WHERE schema_name LIKE 'novoed%' ORDER BY courseName;";
        echo "<b>NovoEd courses archived on Datastage</b><br>";
    }

    // Execute the query (the recordset $rs contains the result)
    $rs = mysql_query($strSQL);

    // Loop the recordset $rs
    // Each row will be made into an array ($row) using mysql_fetch_array
    while($row = mysql_fetch_array($rs)) {
        // Write the value back to the browser:
        echo $row['courseName'] . "<br />";
    }

   // Close the database connection
    mysql_close();
}

function prepDb() {
    // Connect to database server
    mysql_connect("datastage.stanford.edu", "paepcke", "r0d1ngard3n") or die (mysql_error ());
    // Select database
    mysql_select_db("Edx") or die(mysql_error());
}

?>