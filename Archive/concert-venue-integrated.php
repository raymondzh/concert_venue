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

  <h2>Insert new Staff - UPDATES STAFF TABLE ONLY</h2>
  <p>staffID is a unique int and names are case sensitive (will be saved as they're entered).</p>
  <form method="POST" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    staffID: <input type="int" name="staffID"> <br /><br />
    First Name: <input type="text" name="firstname"> <br /><br />
    Last Name: <input type="text" name="lastname"> <br /><br />

    <input type="submit" value="Insert" name="insertSubmit"></p>
  </form>

  <hr />

  <h2>Update First Name in Staff - UPDATES STAFF TABLE ONLY</h2>
  <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do
    anything.</p>

  <form method="POST" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
    Old Name: <input type="text" name="oldName"> <br /><br />
    New Name: <input type="text" name="newName"> <br /><br />

    <input type="submit" value="Update" name="updateSubmit"></p>
  </form>

  <hr />

  <h2>Count the Tuples in Staff - COUNTS STAFF TABLE ONLY</h2>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="countTupleRequest" name="countTupleRequest">
    <input type="submit" name="countTuples"></p>
  </form>

  <h2>Display Tuples in a Staff - DISPLAYS STAFF TABLE ONLY</h2>
  <form method="GET" action="concert-venue.php">
    <!--refresh page when submitted-->
    <input type="hidden" id="displayTuplesRequest" name="displayTuplesRequest">
    <input type="submit" name="displayTuples"></p>
  </form>



  <?php
  // parsing php
  //------------------------------------------------
  //------------------------------------------------
  //------------------------------------------------
  //------------------------------------------------
  //------------------------------------------------


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


  function printResult($result)
  { //prints results from a select statement
    echo "<br>Retrieved data from table Staff:<br>";
    echo "<table>";
    echo "<tr><th>staffID</th><th>FirstName</th><th>LastName</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "<tr><td>" . $row["STAFFID"] . "</td><td>" . $row["FIRSTNAME"] . "</td><td>" . $row["LASTNAME"] . "</td></tr>"; //or just use "echo $row[0]" 
      // echo $row[0];
    }

    echo "</table>";
  }

  function connectToDB()
  {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_ykim07", "a55441778", "dbhost.students.cs.ubc.ca:1522/stu");

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
  //------------------------------------------------
  //------------------------------------------------
  //------------------------------------------------

  function handleUpdateRequest()
  {
    global $db_conn;

    $old_name = $_POST['oldName'];
    $new_name = $_POST['newName'];

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE Staff SET firstname='" . $new_name . "' WHERE firstname='" . $old_name . "'");
    OCICommit($db_conn);
  }

  function handleResetRequest()
  {
    global $db_conn;
    // Drop old table
    // executePlainSQL("drop table Staff");

    $query = file_get_contents('concert-venue.sql');
    $commands = explode(';', $query);

    foreach ($commands as $command) {
      executePlainSQL($command);
    }
    //executePlainSQL("drop table SeatingSection");
    /*
    executePlainSQL("CREATE TABLE SeatingSection(
      sectnum int,
      price int,
      PRIMARY KEY (sectnum))");
    */

    echo "<br> resetting <br>";
    OCICommit($db_conn);
    // executePlainSQL("drop table Performer");
    // executePlainSQL("CREATE TABLE Performer(
    //     pname char(20),
    //     cname char(20),
    //     PRIMARY KEY (pname),
    //     FOREIGN KEY (cname) REFERENCES EntertainmentCompany
    //     ON DELETE CASCADE)");
  }

  function handleInsertRequest() {
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

function handleCountRequest() {
  global $db_conn;

  $result = executePlainSQL("SELECT Count(*) FROM Staff");

  if (($row = oci_fetch_row($result)) != false) {
      echo "<br> The number of tuples in Staff: " . $row[0] . "<br>";
  }
}

function handleDisplayRequest() {
  global $db_conn;

  $result = executePlainSQL("SELECT * FROM Staff");

  printResult($result);
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
      }

      disconnectFromDB();
  }
}

if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
  handlePOSTRequest();
} else if (isset($_GET['countTupleRequest'])) {
  handleGETRequest();
} else if (isset($_GET['displayTuplesRequest'])) {
  handleGETRequest();
}
?>
</body>

</html>