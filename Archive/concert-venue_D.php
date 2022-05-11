<!DOCTYPE html>

<html>

<head>
  <title>CPSC 304 Group Project</title>
</head>

<body>
  <h1>CPSC 304 Group Project (tedious version)</h1>
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

  <h2>Count Tuples</h2>
  <p>Returns the number of tuples in specified table.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="countTupleRequest" name="countTupleRequest">
    <input type="text" id="countTuplesInput" name="countTuplesInput">
    <input type="submit" name="countTuples"></p>
  </form>

  <h2>Display Tuples</h2>
  <p>Displays specified table.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="displayTuplesRequest" name="displayTuplesRequest">
    <input type="text" id="displayTuplesInput" name="displayTuplesInput">
    <input type="submit" name="displayTuples"></p>
  </form>

  <h2>Display Tables List</h2>
  <p>All tables currently in the database.</p>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="displayTablesRequest" name="displayTablesRequest">
    <input type="submit" name="displayTables"></p>
  </form>


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

  function handleCountRequest() 
  {
    global $db_conn;

    $tableName = $_GET['countTuplesInput'];
    $result = executePlainSQL("SELECT Count(*) FROM $tableName");

    if (($row = oci_fetch_row($result)) != false) {
      echo "<br> The number of tuples in Staff: " . $row[0] . "<br>";
    }
  }

  function handleDisplayRequest() 
  {
    global $db_conn;

    $tableName = $_GET['displayTuplesInput'];
    $result = executePlainSQL("SELECT * FROM $tableName");

    printResult($result, $tableName);
  }

  function handleDisplayTablesRequest() 
  {
    global $db_conn;

    $result = executePlainSQL("SELECT table_name from user_tables");

    printTableResult($result);
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
      }

      disconnectFromDB();
  }
}

if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit'])) {
  handlePOSTRequest();
} else if (isset($_GET['countTupleRequest'])) {
  handleGETRequest();
} else if (isset($_GET['displayTuplesRequest'])) {
  handleGETRequest();
} else if (isset($_GET['displayTablesRequest'])) {
  handleGETRequest();
}
?>
</body>

</html>