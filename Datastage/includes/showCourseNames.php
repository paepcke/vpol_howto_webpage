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

    $mySQLDb =& prepDb();

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
        $strSQL = "SELECT course_display_name AS courseName,
	                  enrollment(course_display_name) AS enrollment,
			  -- academic_year,
			  -- quarter,
        start_date,
        is_internal
                   FROM CourseInfo ORDER BY courseName;";

        echo "<b>OpenEdX courses archived on Datastage</b><br>";
	echo "<i>Courses in <span class=sharable>green</span> may be shared with researchers outside of Stanford</i><br><br>";
	echo "<b>Course,Enrollment</b><br>";


    } elseif ($platform == 'novoed') {
        $strSQL = "SELECT DISTINCT extractNovoEdCourseName(SCHEMA_NAME) AS courseName
                   FROM information_schema.SCHEMATA
                   WHERE schema_name LIKE 'novoed%' ORDER BY courseName;";
        echo "<b>NovoEd courses archived on Datastage</b><br>";
    }

    // Execute the query (the recordset $result contains the result
    // an a php array):

    if (!$result = $mySQLDb->query($strSQL)) {
        die('There was an error running the query [' . $mySQLDb->error . ']');
	}

    // Loop the recordset $result
    // Each row will be made into an array ($row) using fetch_array()

    while($row = $result->fetch_assoc()) {
      if ($platform != 'openedx') {
        // Write the value back to the browser:
        echo $row['courseName'] . "<br />";
      } else {
          // Asking for OpenEdX courses:

	  // Get rid of the bogus 'connecting to: modulestore':
	  if ($row['courseName'] == 'connecting to: modulestore' ||
	      $row['courseName'] == 'course_display_name') {
	      continue;
	  }

          //*********
          // echo "ran: " . $row['academic_year'] . ':' . $row['quarter'] . '<br>';
          //*********

          // if ($row['academic_year'] > 2014 ||
          //     ($row['academic_year'] == 2014 && (  ($row['quarter'] == 'summer')
	      	// 		     	         || $row['quarter'] == 'fall'))) {
             if (($row['start_date' > '2014-06-14') && ($row['is_internal'] == 0)) {
              echo '<span class="sharable">' . $row['courseName'] . ' (enrollment: ' . $row['enrollment'] . ')</span><br />';
          } else {
              echo $row['courseName'] . ' (enrollment: ' . $row['enrollment'] . ")<br />";
          }
      }
    }

    // Close the database connection
    $mySQLDb->close();
}

// The '&' in the following function def indicates that a pointer (to an object)
// will be returned:

function &prepDb() {

    $mySQLDb = new mysqli("localhost", "webnobody", "Rpd$9Q34@?", "Edx");

    if($mySQLDb->connect_errno > 0){
        die('Unable to connect to database [' . $mySQLDb->connect_error . ']');
    }

    return $mySQLDb;
}

?>
