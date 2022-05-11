<!DOCTYPE html>

<html>

<head>
  <title>CPSC 304 Group Project</title>
</head>

<body>
  <h1>CPSC 304 Group Project</h1>
  <h2>d1w5a, k3x2b, s8b3b</h2>
  <h2>Concert Venue Simulator</h2>

  <h2>Reset</h2>
  <p>Reset tables to pre-populated data</p>
  <form method="POST" action="concert-venue.php">
    <!-- if you want another page to load after the button is clicked, you have to specify that page in the
action parameter -->
    <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
    <p><input type="submit" value="Reset" name="reset"></p>
  </form>

  <hr />

  <h2>Insert</h2>
  <p>Add a new staff member to the venue's database.</p>
  <p>staffID is a unique int. Names are case sensitive and will be saved as they're entered.</p>
  <form method="POST" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    staffID: <input type="int" name="staffID"> <br /><br />
    First Name: <input type="text" name="firstname"> <br /><br />
    Last Name: <input type="text" name="lastname"> <br /><br />

    <input type="submit" value="Insert" name="insertSubmit"></p>
  </form>

  <hr />

  <h2>Update</h2>
  <p>Update a staff member's information. staffID cannot be changed.</p>
  <p>Names are case sensitive and will be saved as they're entered.</p>
  <form method="POST" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
    staffID: <input type="text" name="staffid"> <br /><br />
    New First Name: <input type="text" name="newfName"> <br /><br />
    New Last Name: <input type="text" name="newlName"> <br /><br />

    <input type="submit" value="Update" name="updateSubmit"></p>
  </form>

  <hr />

  <h2>Delete</h2>
  <p>Delete a staff member from the venue's database.</p>
  <form method="POST" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
    staffID: <input type="text" name="staffid"> <br /><br />

    <input type="submit" value="Delete" name="deleteSubmit"></p>
  </form>

   <hr />

  <h2>Search - Selection</h2>
  <p>The values are case sensitive.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="displayTuplesRequest" name="displayTuplesRequest">
    <label for="tableInput">Table name: </label>
    <input type="text" id="tableInput" name="tableInput"><br>
    <label for="attributeInput">Attributes (separated by comma ','): </label>
    <input type="text" id="attributeInput" name="attributeInput"><br>
    <label for="conditionInput">Conditions (separated by comma ','): </label>
    <input type="text" id="conditionInput" name="conditionInput"><br>
    <input type="submit" name="selectionTuples"></p>
  </form>

   <hr />

  <h2>Attendee Seat Table - Projection</h2>
  <form method="GET" action="concert-venue.php">
    <input type="hidden" name="displayTuplesRequest">
    <input type="checkbox" name="attribute[]" value="name">
    <label for="attribute1">full name</label><br>
    <input type="checkbox" name="attribute[]" value="cardnum">
    <label for="attribute2">card number</label><br>
    <input type="checkbox" name="attribute[]" value="sectnum">
    <label for="attribute3">section number</label><br>
    <input type="checkbox" name="attribute[]" value="seatnum">
    <label for="attributes4">seat number</label><br>
    <input type="submit" name="projectionTuples"></p>
  </form>

  <hr />

  <h2>Search by Performance - Join</h2>
  <p>Find the reservation number and card number of an attendee watching performance(s) by a specific performer.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" name="displayTuplesRequest">
    <label for="tableInput">Performer name: </label>
    <input type="text" name="performanceName"><br>
    <input type="submit" name="joinTuples"></p>
  </form>

  <hr />

  <h2>Search Security Staff - Division</h2>
  <p>Find security staff who have protected all artists. They'll get a raise!</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" name="displayTuplesRequest">
    <input type="submit" name="divisionTuples"></p>
  </form>

  <hr />

  <h2>Display Performance Count by Performer</h2>
  <form method="GET" action="concert-venue.php"> <!--refresh page when submitted-->
      <input type="hidden" id="displayPerformanceCountByPerformerRequest" name="displayPerformanceCountByPerformerRequest">
      <input type="submit" name="displayPerformanceCountByPerformer"></p>
  </form>

  <hr />

  <h2>Display Performances with Specified Minimum Number of Attendees</h2>
  <form method="GET" action="concert-venue.php"> <!--refresh page when submitted-->
      <input type="hidden" id="displayPerformanceWithMinimumAttendeesRequest" name="displayPerformanceWithMinimumAttendeesRequest">
      Minimum Number of Attendees: <input type="text" name="Num"> <br /><br />
      <input type="submit" name="displayPerformanceWithMinimumAttendees"></p>

  </form>

  <hr />

   <h2>Display Minimum Duration Performance by type, over a Length with Greater Than Average Duration</h2>
   <p>Returns the shortest performance > the specified duration (in minutes) for each performance type, for which the average duration is longer than the average duration of all the performances in the database.</p>
   <form method="GET" action="concert-venue.php"> <!--refresh page when submitted-->
       <input type="hidden" id="displayShortestPerformanceOverRequest" name="displayShortestPerformanceOverRequest">
       Minimum Duration of Performance over: <input type="text" name="Num"> <br /><br />
       <input type="submit" name="displayShortestPerformanceOver"></p>

   </form>

  <hr />

  <h2>Count Tuples</h2>
  <p>Returns the number of tuples in specified table.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="countTupleRequest" name="countTupleRequest">
    <input type="text" id="countTuplesInput" name="countTuplesInput">
    <input type="submit" name="countTuples"></p>
  </form>

  <hr />

  <h2>Display Tuples</h2>
  <p>Displays specified table.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="displayTuplesRequest" name="displayTuplesRequest">
    <input type="text" id="displayTuplesInput" name="displayTuplesInput">
    <input type="submit" name="displayTuples"></p>
  </form>

  <hr />

  <h2>Display Tables List</h2>
  <p>All tables currently in the database.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="displayTablesRequest" name="displayTablesRequest">
    <input type="submit" name="displayTables"></p>
  </form>

  <hr />

  <h2>Result</h2>


  <?php
  // parsing php --------

  $success = True; //keep track of errors so it redirects the page only if there are no errors
  $db_conn = NULL; // edit the login credentials in connectToDB()
  $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

  function debugAlertMessage($message)
  {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
      echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
  }

  function executePlainSQL($cmdstr)
  { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
      echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
      $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
      echo htmlentities($e['message']);
      $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
      echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
      $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
      echo htmlentities($e['message']);
      $success = False;
    }

    return $statement;
  }

  function executeBoundSQL($cmdstr, $list)
  {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
      echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
      $e = OCI_Error($db_conn);
      echo htmlentities($e['message']);
      $success = False;
    }

    foreach ($list as $tuple) {
      foreach ($tuple as $bind => $val) {
        //echo $val;
        //echo "<br>".$bind."<br>";
        OCIBindByName($statement, $bind, $val);
        unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
      }

      $r = OCIExecute($statement, OCI_DEFAULT);
      if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
        echo htmlentities($e['message']);
        echo "<br>";
        $success = False;
      }
    }
  }


  function connectToDB()
    {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_dsun17", "a41134776", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
      debugAlertMessage("Database is Connected");
      return true;
    } else {
      debugAlertMessage("Cannot connect to Database");
      $e = OCI_Error(); // For OCILogon errors pass no handle
      echo htmlentities($e['message']);
      return false;
    }
  }

  function disconnectFromDB()
  {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
  }

  //------------------------------------------------

  function handleResetRequest()
  {
    global $db_conn;

    $query = file_get_contents('concert-venue.sql');
    $commands = explode(';', $query);

    foreach ($commands as $command) {
      executePlainSQL($command);
    }

    echo "<br> resetting <br>";
    OCICommit($db_conn);
  }

  function handleInsertRequest()
  {
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array (
        ":bind1" => $_POST['staffID'],
        ":bind2" => $_POST['firstname'],
        ":bind3" => $_POST['lastname']
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into Staff values (:bind1, :bind2, :bind3)", $alltuples);
    OCICommit($db_conn);
  }

  function handleUpdateRequest()
  {
    global $db_conn;

    $id = $_POST['staffid'];
    $new_fname = $_POST['newfName'];
    $new_lname = $_POST['newlName'];

    // you need the wrap the old name and new name values with single quotations
    if (!empty($new_fname)) {
      executePlainSQL("UPDATE Staff SET firstname='" . $new_fname . "' WHERE staffID='" . $id . "'");
    }

    if (!empty($new_lname)) {
      executePlainSQL("UPDATE Staff SET lastname='" . $new_lname . "'WHERE staffID='" . $id. "'");
    }

    OCICommit($db_conn);
  }

  function handleDeleteRequest()
  {
    global $db_conn;

    $id = $_POST['staffid'];

    executePlainSQL("DELETE from Staff WHERE staffID='" . $id . "'");

    OCICommit($db_conn);
  }

  function handleSelectionRequest()
  {
    global $db_conn;
    $table = $_GET['tableInput'];
    $attribute = $_GET['attributeInput'];
    $condition = str_replace(',', ' AND ', $_GET['conditionInput']);

    $result = executePlainSQL("SELECT $attribute FROM $table WHERE $condition");

    printResult($result, $table);
  }

  function handleProjectionRequest()
  {
    global $db_conn;
    $atts = $_GET['attribute'];
    $selected = $atts[0];

    if (empty($atts)) {
      echo "<br> You did not choose any attributes <br>";
    } else {
      foreach ($atts as $att) {
        if ($atts[0] != $att) {
          $selected = $selected . ", " . $att;
        }
      }
      $result = executePlainSQL("SELECT $selected FROM Attendee_Seat_2");

      printResult($result, "Attendee_Seat_2");
    }
  }

  function handleJoinRequest()
  {
    $pname = $_GET['performanceName'];
    $result = executePlainSQL("SELECT DISTINCT resnum, cardnum FROM Attendee_Seat_1 NATURAL JOIN Watch WHERE pname = '$pname'");

    printResult($result, "Attendee_Seat_1 and Watch");
  }

  function handleDivisionRequest()
  {
    $result = executePlainSQL(
      "SELECT *
      FROM staff s
      WHERE NOT EXISTS (SELECT p1.pname
                        FROM performer p1
                        WHERE NOT EXISTS (SELECT p2.pname
                                          FROM protect p2
                                          WHERE p2.staffID = s.staffID
                                          AND p1.pname = p2.pname))");

    printResult($result, "Staff, Performer and Protect");
  }

  function handleDisplayPerformanceCountByPerformer() {
    global $db_conn;

    $result = executePlainSQL(
      "SELECT pname, count(*)
      FROM Performance_1
      GROUP BY pname");

    echo "<br>Number of performances by performer:<br>";
    echo "<table>";
    echo "<tr><th>Performer</th><th>Number of performances</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
  }

  function displayPerformanceWithMinimumAttendees() {
    global $db_conn;

    $command = "SELECT DISTINCT pname, pdate
    FROM Watch w
    GROUP BY w.pname, w.pdate
    HAVING count(*) >= ";

    $command .= $_GET['Num'];

    $result = executePlainSQL($command);

    echo "<br>Performances:<br>";
    echo "<table>";
    echo "<tr><th>Performance</th><th>Date</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
  }

  function displayShortestPerformanceOver() {
     global $db_conn;

     $command = "SELECT MIN(duration), type
     FROM Performance_2
     WHERE duration > ". $_GET['Num'] .
     "GROUP BY type
     HAVING AVG(duration) > (SELECT AVG(duration)
                             FROM   Performance_2)";

     $result = executePlainSQL($command);

     echo "<br>Performances:<br>";
     echo "<table>";
     echo "<tr><th>Duration</th><th>type</th></tr>";

     while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
       echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
     }

     echo "</table>";
   }

   function handleCountRequest()
  {
    global $db_conn;

    $tableName = $_GET['countTuplesInput'];
    $result = executePlainSQL("SELECT Count(*) FROM $tableName");

    if (($row = oci_fetch_row($result)) != false) {
      echo "<br> The number of tuples in Staff: " . $row[0] . "<br>";
    }
  }

  function printResult($result, $tableName)
  { //prints results from a select statement
    echo "<br>Retrieved data from table $tableName:<br>";
    echo "<table>";
    echo "<table align='left' cellspacing=4 cellpadding=4>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
      // echo $row[0];
    }

    echo "</table>";
  }

  function handleDisplayRequest()
  {
    global $db_conn;

    $tableName = $_GET['displayTuplesInput'];
    $result = executePlainSQL("SELECT * FROM $tableName");

    printResult($result, $tableName);
  }

  function printTableResult($result)
  { //prints results from a select statement
    echo "<br>Retrieved all table names:<br>";
    echo "<table>";
    echo "<tr><th>Table_Name</th></tr>";

    echo $result;
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "<tr><td>" . $row[0] . "</td></tr>"; //or just use "echo $row[0]"
      // echo $row[0];
    }

    echo "</table>";
  }
  
  function handleDisplayTablesRequest()
  {
    global $db_conn;

    $result = executePlainSQL("SELECT table_name from user_tables");

    printTableResult($result);
  }
  

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
  if (connectToDB()) {
      if (array_key_exists('resetTablesRequest', $_POST)) {
          handleResetRequest();
      } else if (array_key_exists('updateQueryRequest', $_POST)) {
          handleUpdateRequest();
      } else if (array_key_exists('insertQueryRequest', $_POST)) {
          handleInsertRequest();
      } else if (array_key_exists('deleteQueryRequest', $_POST)) {
          handleDeleteRequest();
      }

      disconnectFromDB();
  }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
  if (connectToDB()) {
      if (array_key_exists('countTuples', $_GET)) {
          handleCountRequest();
      } else if (array_key_exists('displayTuples', $_GET)) {
          handleDisplayRequest();
      } else if (array_key_exists('displayTables', $_GET)) {
          handleDisplayTablesRequest();
      } else if (array_key_exists('displayPerformanceCountByPerformer', $_GET)) {
        handleDisplayPerformanceCountByPerformer();
      } else if (array_key_exists('displayPerformanceWithMinimumAttendees', $_GET)) {
        displayPerformanceWithMinimumAttendees();
      } else if (array_key_exists('selectionTuples', $_GET)) {
        handleSelectionRequest();
      } else if (array_key_exists('projectionTuples', $_GET)) {
        handleProjectionRequest();
      } else if (array_key_exists('joinTuples', $_GET)) {
        handleJoinRequest();
      } else if (array_key_exists('divisionTuples', $_GET)) {
        handleDivisionRequest();
      } else if (array_key_exists('displayShortestPerformanceOver', $_GET)) {
        displayShortestPerformanceOver();
      }

      disconnectFromDB();
  }
}

if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit'])) {
  handlePOSTRequest();
} else if (isset($_GET['displayTupleRequest']) || isset($_GET['displayPerformanceCountByPerformerRequest']) || isset($_GET['displayPerformanceWithMinimumAttendeesRequest'])) {
  handleGETRequest();
} else if (isset($_GET['displayTuplesRequest'])) {
  handleGETRequest();
} else if (isset($_GET['displayTablesRequest'])) {
  handleGETRequest();
} else if (isset($_GET['displayShortestPerformanceOverRequest'])) {
  handleGETRequest();
} 

?>
</body>

</html>