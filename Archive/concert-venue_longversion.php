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
        <form method="POST" action="concert-venue.php"> <!--refresh page when submitted-->
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

        <form method="POST" action="concert-venue.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Count the Tuples in Staff - COUNTS STAFF TABLE ONLY</h2>
        <form method="GET" action="concert-venue.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        <h2>Display Tuples in a Staff - DISPLAYS STAFF TABLE ONLY</h2>
      	<form method="GET" action="concert-venue.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTuplesRequest" name="displayTuplesRequest">
            <input type="submit" name="displayTuples"></p>
        </form>

        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
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

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables 
involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to 
only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL 
injection. 
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
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
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

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Staff:<br>";
            echo "<table>";
            echo "<tr><th>staffID</th><th>FirstName</th><th>LastName</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["STAFFID"] . "</td><td>" . $row["FIRSTNAME"] . "</td><td>" . $row["LASTNAME"] . "</td></tr>"; //or just use "echo $row[0]" 
                // echo $row[0];
            }

            echo "</table>";
        }

        function connectToDB() {
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

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleUpdateRequest() {
            global $db_conn;

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Staff SET firstname='" . $new_name . "' WHERE firstname='" . $old_name . "'");
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old tables
            executePlainSQL("DROP TABLE Meet");
            executePlainSQL("DROP TABLE Protect");
            executePlainSQL("DROP TABLE Watch");
            executePlainSQL("DROP TABLE VIP");
            executePlainSQL("DROP TABLE GeneralAdmission");
            executePlainSQL("DROP TABLE Attendee_Seat_1");
            executePlainSQL("DROP TABLE Attendee_Seat_2");
            executePlainSQL("DROP TABLE Assist");
            executePlainSQL("DROP TABLE In_Charge_Of");
            executePlainSQL("DROP TABLE Security");
            executePlainSQL("DROP TABLE Technician");
            executePlainSQL("DROP TABLE Volunteer");
            executePlainSQL("DROP TABLE Staff");
            executePlainSQL("DROP TABLE SeatingSection");
            executePlainSQL("DROP TABLE Performance_1");
            executePlainSQL("DROP TABLE Travel_Entourage");
            executePlainSQL("DROP TABLE Performer");
            executePlainSQL("DROP TABLE Performance_2");
            executePlainSQL("DROP TABLE EntertainmentCompany");

            // Create new tables
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE SeatingSection(
                sectnum int,
                price int,
                PRIMARY KEY (sectnum))");
            executePlainSQL("grant select on SeatingSection to public");
            executePlainSQL("CREATE TABLE Attendee_Seat_2(
                name char(20),
                cardnum int,
                sectnum int NOT NULL,
                seatnum int NOT NULL,
                PRIMARY KEY (cardnum),
                FOREIGN KEY (sectnum) REFERENCES SeatingSection
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Attendee_Seat_2 to public");
            executePlainSQL("CREATE TABLE Attendee_Seat_1(
                resnum int,
                cardnum int NOT NULL,
                PRIMARY KEY (resnum),
                FOREIGN KEY (cardnum) REFERENCES Attendee_Seat_2
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Attendee_Seat_1 to public");
            executePlainSQL("CREATE TABLE GeneralAdmission(
                resnum int,
                PRIMARY KEY (resnum),
                FOREIGN KEY (resnum) REFERENCES Attendee_Seat_1
                ON DELETE CASCADE)");
            executePlainSQL("grant select on GeneralAdmission to public");
            executePlainSQL("CREATE TABLE VIP(
                resnum int,
                PRIMARY KEY (resnum),
                FOREIGN KEY (resnum) REFERENCES Attendee_Seat_1
                ON DELETE CASCADE)");
            executePlainSQL("grant select on VIP to public");
            executePlainSQL("CREATE TABLE Staff(
                staffID int,
                firstname char(20),
                lastname char(20),
                PRIMARY KEY (staffID))");
            executePlainSQL("grant select on Staff to public");
            executePlainSQL("CREATE TABLE Volunteer(
                staffID int,
                PRIMARY KEY (staffID),
                FOREIGN KEY (staffID) REFERENCES Staff
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Volunteer to public");
            executePlainSQL("CREATE TABLE Technician(
                staffID int,
                specialization char(20),
                PRIMARY KEY (staffID),
                FOREIGN KEY (staffID) REFERENCES Staff
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Technician to public");
            executePlainSQL("CREATE TABLE Security(
                staffID int,
                company char(20),
                PRIMARY KEY (staffID),
                FOREIGN KEY (staffID) REFERENCES Staff
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Security to public");
            executePlainSQL("CREATE TABLE EntertainmentCompany(
                cname char(20),
                PRIMARY KEY (cname))");
            executePlainSQL("grant select on EntertainmentCompany to public");
            executePlainSQL("CREATE TABLE Performer(
                pname char(20),
                cname char(20),
                PRIMARY KEY (pname),
                FOREIGN KEY (cname) REFERENCES EntertainmentCompany
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Performer to public");
            executePlainSQL("CREATE TABLE Travel_Entourage(
                ename char(20),
                relationship char(20),
                pname char(20),
                PRIMARY KEY (ename, pname),
                FOREIGN KEY (pname) REFERENCES Performer
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Travel_Entourage to public");
            executePlainSQL("CREATE TABLE Performance_2(
                title char(20),
                type char(20),
                duration int,
                PRIMARY KEY (title))");
            executePlainSQL("grant select on Performance_2 to public");
            executePlainSQL("CREATE TABLE Performance_1(
                pdate date,
                pname char(20),
                title char(20),
                PRIMARY KEY (pname, pdate),
                FOREIGN KEY (pname) REFERENCES Performer
                ON DELETE CASCADE,
                FOREIGN KEY (title) REFERENCES Performance_2
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Performance_1 to public");
            executePlainSQL("CREATE TABLE In_Charge_Of(
                staffID int,
                sectnum int,
                shift char(20),
                PRIMARY KEY (staffID, sectnum),
                FOREIGN KEY (staffID) REFERENCES Staff
                ON DELETE SET NULL,
                FOREIGN KEY (sectnum) REFERENCES SeatingSection
                ON DELETE CASCADE)");
            executePlainSQL("grant select on In_Charge_Of to public");
            executePlainSQL("CREATE TABLE Protect(
                staffID int,
                pname char(20),
                PRIMARY KEY (staffID, pname),
                FOREIGN KEY (staffID) REFERENCES Security
                ON DELETE CASCADE,
                FOREIGN KEY (pname) REFERENCES Performer
                ON DELETE SET NULL)");
            executePlainSQL("grant select on Protect to public");
            executePlainSQL("CREATE TABLE Assist(
                staffID int,
                pname char(20),
                PRIMARY KEY (staffID, pname),
                FOREIGN KEY (staffID) REFERENCES Technician
                ON DELETE CASCADE,
                FOREIGN KEY (pname) REFERENCES Performer
                ON DELETE SET NULL)");
            executePlainSQL("grant select on Assist to public");
            executePlainSQL("CREATE TABLE Watch(
                resnum int,
                pname char(20),
                pdate date,
                PRIMARY KEY (resnum, pname, pdate),
                FOREIGN KEY (resnum) REFERENCES Attendee_Seat_1
                ON DELETE CASCADE,
                FOREIGN KEY (pname, pdate) REFERENCES Performance_1
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Watch to public");
            executePlainSQL("CREATE TABLE Meet(
                resnum int,
                pname char(20),
                PRIMARY KEY (resnum, pname),
                FOREIGN KEY (resnum) REFERENCES VIP
                ON DELETE CASCADE,
                FOREIGN KEY (pname) REFERENCES Performer
                ON DELETE CASCADE)");
            executePlainSQL("grant select on Meet to public");

            executePlainSQL("insert into SeatingSection values(100, 140)");
            executePlainSQL("insert into SeatingSection values(300, 70)");
            executePlainSQL("insert into SeatingSection values(203, 110)");
            executePlainSQL("insert into SeatingSection values(101, 150)");
            executePlainSQL("insert into SeatingSection values(105, 150)");
            executePlainSQL("insert into SeatingSection values(209, 100)");
            executePlainSQL("insert into SeatingSection values(222, 100)");
            executePlainSQL("insert into SeatingSection values(102, 135)");
            executePlainSQL("insert into SeatingSection values(301, 70)");
            executePlainSQL("insert into SeatingSection values(109, 140)");
            executePlainSQL("insert into Attendee_Seat_2 values('Andy Dwyer', 1234567890123456, 100, 2)");
            executePlainSQL("insert into Attendee_Seat_2 values('April Ludgate', 1324567890123456, 300, 3)");
            executePlainSQL("insert into Attendee_Seat_2 values('Ron Swanson', 1423567890123456, 203, 14)");
            executePlainSQL("insert into Attendee_Seat_2 values('Leslie Knope', 1523467890123456, 101, 76)");
            executePlainSQL("insert into Attendee_Seat_2 values('Tom Haverford', 1623457890123456, 105, 57)");
            executePlainSQL("insert into Attendee_Seat_2 values('Jerry Gergich', 1723456890123456, 209, 3)");
            executePlainSQL("insert into Attendee_Seat_2 values('Ann Perkins', 1823456790123456, 222, 35)");
            executePlainSQL("insert into Attendee_Seat_2 values('Ben Wyatt', 1923456780123456, 102, 32)");
            executePlainSQL("insert into Attendee_Seat_2 values('Chris Traeger', 1023456789123456, 301, 14)");
            executePlainSQL("insert into Attendee_Seat_2 values('Donna Meagle', 2134567890123456, 109, 3)");
            
            executePlainSQL("insert into Attendee_Seat_1 values(35142, 1234567890123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(49532, 1324567890123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(52341, 1423567890123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(21435, 1523467890123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(31524, 1623457890123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(79384, 1723456890123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(92448, 1823456790123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(41245, 1923456780123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(21433, 1023456789123456)");
            executePlainSQL("insert into Attendee_Seat_1 values(35193, 2134567890123456)");

            executePlainSQL("insert into GeneralAdmission values(35142)");
            executePlainSQL("insert into GeneralAdmission values(49532)");
            executePlainSQL("insert into GeneralAdmission values(52341)");
            executePlainSQL("insert into GeneralAdmission values(79384)");
            executePlainSQL("insert into GeneralAdmission values(41245)");

            executePlainSQL("insert into VIP values(21435)");
            executePlainSQL("insert into VIP values(31524)");
            executePlainSQL("insert into VIP values(92448)");
            executePlainSQL("insert into VIP values(21433)");
            executePlainSQL("insert into VIP values(35193)");

            executePlainSQL("insert into Staff values(1001, 'Michael', 'Scott')");
            executePlainSQL("insert into Staff values(1002, 'Kevin', 'Malone')");
            executePlainSQL("insert into Staff values(1003, 'Jim', 'Halpert')");
            executePlainSQL("insert into Staff values(1004, 'Pam', 'Beesly')");
            executePlainSQL("insert into Staff values(1005, 'Merideth', 'Palmer')");
            executePlainSQL("insert into Staff values(1006, 'Stanley', 'Hudson')");
            executePlainSQL("insert into Staff values(1007, 'Dwight', 'Schrute')");
            executePlainSQL("insert into Staff values(1008, 'Angela', 'Martin')");
            executePlainSQL("insert into Staff values(1009, 'Oscar', 'Martinez')");
            executePlainSQL("insert into Staff values(1010, 'Phyllis', 'Vance')");
            executePlainSQL("insert into Staff values(1011, 'Kelly', 'Kapur')");
            executePlainSQL("insert into Staff values(1012, 'Ryan', 'Howard')");
            executePlainSQL("insert into Staff values(1013, 'Toby', 'Flenderson')");
            executePlainSQL("insert into Staff values(1014, 'Daryl', 'Philbin')");
            executePlainSQL("insert into Staff values(1015, 'Jan', 'Levinson')");

            executePlainSQL("insert into Volunteer values(1001)");
            executePlainSQL("insert into Volunteer values(1005)");
            executePlainSQL("insert into Volunteer values(1009)");
            executePlainSQL("insert into Volunteer values(1010)");
            executePlainSQL("insert into Volunteer values(1011)");

            executePlainSQL("insert into Technician values(1002, 'lighting')");
            executePlainSQL("insert into Technician values(1004, 'sound')");
            executePlainSQL("insert into Technician values(1006, 'lighting')");
            executePlainSQL("insert into Technician values(1008, 'sound')");
            executePlainSQL("insert into Technician values(1013, 'electrical')");

            executePlainSQL("insert into Security values(1003, 'Securitee')");
            executePlainSQL("insert into Security values(1007, 'Securitee')");
            executePlainSQL("insert into Security values(1012, 'SecureRUs')");
            executePlainSQL("insert into Security values(1014, 'SecureRUs')");
            executePlainSQL("insert into Security values(1015, 'SeSecure')");

            executePlainSQL("insert into EntertainmentCompany values('Entertainment 720')");
            executePlainSQL("insert into EntertainmentCompany values('Big Records')");
            executePlainSQL("insert into EntertainmentCompany values('Sick Beats')");
            executePlainSQL("insert into EntertainmentCompany values('Hype Tunez')");
            executePlainSQL("insert into EntertainmentCompany values('XL Entertainment')");

            executePlainSQL("insert into Performer values('Duke Silver', 'Entertainment 720')");
            executePlainSQL("insert into Performer values('Scrantonicity', 'Big Records')");
            executePlainSQL("insert into Performer values('Mouse Rat', 'Hype Tunez')");
            executePlainSQL("insert into Performer values('Ping', 'XL Entertainment')");
            executePlainSQL("insert into Performer values('DJ Disco', 'Sick Beats')");

            executePlainSQL("insert into Travel_Entourage values('Diane Lewis', 'wife', 'Duke Silver')");
            executePlainSQL("insert into Travel_Entourage values('Jon Swanson', 'son', 'Duke Silver')");
            executePlainSQL("insert into Travel_Entourage values('Ting', 'friend', 'Ping')");
            executePlainSQL("insert into Travel_Entourage values('King', 'friend', 'Ping')");
            executePlainSQL("insert into Travel_Entourage values('Crystahl', 'girlfriend', 'DJ Disco')");

            executePlainSQL("insert into Performance_2 values('Smooth as Silver', 'jazz', 120)");
            executePlainSQL("insert into Performance_2 values('Hahaha', 'comedy', 90)");
            executePlainSQL("insert into Performance_2 values('Hahaha2', 'comedy', 90)");
            executePlainSQL("insert into Performance_2 values('Reunion Show', 'rock', 120)");
            executePlainSQL("insert into Performance_2 values('Mouse Rat', 'rock', 150)");
            executePlainSQL("insert into Performance_2 values('DJ Disco Halloween', 'rock', 180)");

            executePlainSQL("insert into Performance_1 values(TO_DATE('2022-02-13', 'YYYY-MM-DD'), 'Duke Silver', 'Smooth as Silver')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2022-02-14', 'YYYY-MM-DD'), 'Duke Silver', 'Smooth as Silver')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2023-02-14', 'YYYY-MM-DD'), 'Ping', 'Hahaha2')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2022-04-24', 'YYYY-MM-DD'), 'Ping', 'Hahaha')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2023-09-29', 'YYYY-MM-DD'), 'Ping', 'Hahaha2')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2022-06-30', 'YYYY-MM-DD'), 'Scrantonicity', 'Reunion Show')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2024-08-15', 'YYYY-MM-DD'), 'Mouse Rat', 'Mouse Rat')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2021-10-30', 'YYYY-MM-DD'), 'DJ Disco', 'DJ Disco Halloween')");
            executePlainSQL("insert into Performance_1 values(TO_DATE('2021-10-31', 'YYYY-MM-DD'), 'DJ Disco', 'DJ Disco Halloween')");

            executePlainSQL("insert into In_Charge_Of values(1001, 100, '13/02/2022')");
            executePlainSQL("insert into In_Charge_Of values(1005, 209, '13/02/2022')");
            executePlainSQL("insert into In_Charge_Of values(1009, 300, '15/08/2024')");
            executePlainSQL("insert into In_Charge_Of values(1010, 203, '30/10/2021')");
            executePlainSQL("insert into In_Charge_Of values(1011, 101, '30/10/2021')");

            executePlainSQL("insert into Assist values(1002, 'Duke Silver')");
            executePlainSQL("insert into Assist values(1004, 'Scrantonicity')");
            executePlainSQL("insert into Assist values(1006, 'Mouse Rat')");
            executePlainSQL("insert into Assist values(1008, 'Ping')");
            executePlainSQL("insert into Assist values(1013, 'DJ Disco')");

            executePlainSQL("insert into Watch values(35142, 'Ping', TO_DATE('2022-4-24', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(49532, 'Mouse Rat', TO_DATE('2024-8-15', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(52341, 'Scrantonicity', TO_DATE('2022-6-30', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(21435, 'Duke Silver', TO_DATE('2022-2-14', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(31524, 'DJ Disco', TO_DATE('2021-10-31', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(79384, 'Duke Silver', TO_DATE('2022-2-14', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(92448, 'Mouse Rat', TO_DATE('2024-8-15', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(41245, 'Ping', TO_DATE('2023-9-29', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(21433, 'Scrantonicity', TO_DATE('2022-6-30', 'YYYY-MM-DD'))");
            executePlainSQL("insert into Watch values(35193, 'DJ Disco', TO_DATE('2021-10-31', 'YYYY-MM-DD'))");

            executePlainSQL("insert into Meet values(21435, 'Duke Silver')");
            executePlainSQL("insert into Meet values(31524, 'DJ Disco')");
            executePlainSQL("insert into Meet values(92448, 'Mouse Rat')");
            executePlainSQL("insert into Meet values(21433, 'Scrantonicity')");
            executePlainSQL("insert into Meet values(35193, 'DJ Disco')");

            OCICommit($db_conn);
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
