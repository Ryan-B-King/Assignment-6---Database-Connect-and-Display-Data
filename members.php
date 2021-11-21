<?php
    // SETUP TO DATABASE
    $dsn = "mysql:host=localhost;dbname=college";
    $username = "root";
    $password = "";

    // ERROR MESSAGE
    $msg ="";

    // CONNECT TO DATABASE
    $conn = new PDO($dsn, $username, $password);  // CREATE PDO OBJECT

    // TRY-CATCH TO CONFIRM CONNECTION
    try {
        $conn = new PDO($dsn, $username, $password);
        // print "Connection is successful<br><br>";
    }

    catch (PDOException $e) {
        $error_message = $e->getMessage();
        print "An error occured: $error_message";
    }

    // SQL STATEMENT
    $sql = "SELECT lname, fname, address, city, postal_code, phone, email
            FROM college.members
            -- WHERE city = :city
            ORDER BY postal_code, lname";
    $statement = $conn->prepare($sql);

    // EXECUTE RESULT SET
    $statement->execute();

    $city = "All Cities";

    // ROW COUNT
    $rowcount = $statement->rowCount();

    // TEST ROW COUNT
    // print "Row count for Table is " . $rowcount;

    // DROP DOWN LIST SQL2

    // SPL STATEMENT SETUP FOR DROP DOWN LIST
    $sql2 = "SELECT DISTINCT city FROM college.members";
    $statement2 = $conn->prepare($sql2);

    // EXECUTE (CREATE) THE RESULT SET
    $statement2->execute();

    // ROW COUNT
    $rowcount2 = $statement2->rowCount();

    // JUST TO TEST
    // print "Row count for Select is " . $rowcount2 . "<br>";

    if (($_SERVER['REQUEST_METHOD'] == 'POST') && ($_POST["city"] != "none")) {

        // IF TRUE EXECUTE SQL
        // THIS SQL CODE WILL OVERRIDE THE INISIAL SQL 
        $city = $_POST["city"]; // USED IN QUERY

        // SQL STATEMENT
        $sql = "SELECT lname, fname, address, city, postal_code, phone, email
                FROM college.members
                WHERE city = :city
                ORDER BY postal_code, lname";
        $statement = $conn->prepare($sql);

        // EXECUTE RESULT SET
        $statement->execute([":city" => "$city"]);

        // ROW COUNT
        $rowcount = $statement->rowCount();

        // TEST ROW COUNT
        // print "Row count for Table is " . $rowcount;

    } // END POSTBACK CHECK

    if (($_SERVER['REQUEST_METHOD'] == 'POST') && ($_POST["city"] == "none")) {

        $msg = "Please make a selection.";

    } // END POSTBACK CHECK

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear'])) {

        

    }

?>

<!DOCTYPE html><!-- Ryan King -->
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Student Population</title>
        <style>

            body{
                font: 1.25em Arial, sans-serif;
            }

            div {
                margin: 50px auto;
                display: flex;
                flex-direction: column;
            }

            #tborder {
                margin: 0 auto;
                display: flex;
                border-collapse: collapse;
                border: 3px black solid;
            }

            table {
                border-collapse: collapse;
            }

            th { 
                margin: 0 auto;
                padding: auto;
                min-width: 150px;
                height: 25px;
                text-align: center;
                border-collapse: collapse;
                background-color: darkgrey;
            }

            td {
                padding: 2px 10px;
                width: auto;
                border-top: 1px black solid;
                border-collapse: collapse;
            }

            tr:nth-child(odd) {
                background-color: lightgrey;
            }

            .lborder {
                border-left: 1px black solid;
            }

            #email {
                min-width: 275px;
            }

            h1, h2 {
                display: flex;
                margin: 10px auto;
                text-align: center;
            }

            #msg {
                color: red;
            }

            #msg, form {
                padding-left: 5%;
            }

            form {
                /* display: flex; */
            }

        </style>
    </head>
    <body>
        <div>
            <h1>Student Population by City of Residence</h1>
            <h2><?php print "Number of Students Living in " . $city . ": " . $rowcount ?></h2>
                <?php 
                    
                    // CHECK TO MAKE SURE WE HAVE RECORDS RETURNED FOR DROP DOWN LIST
                    if ($rowcount2 != 0) {
                        
                        // ERROR MESSAGE
                        print "<p id='msg'>$msg</p>\n\r";

                        // BEGIN FORM
                        print "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>\n\r";
                        print "<label for='city'>Select a City:</label>\n\r";
                        print "<select name='city' id='city'>\n\r";
                        print "<option value='none'>Make a Selection</option>\n\r";

                        // OUTPUT DATA OF EACH ROW AS ASSOCIATIVE ARRAY IN RESULT SET
                        $rows = $statement2->fetchAll();

                        // <OPTION> ELEMENT
                        foreach($rows as $row) {
                            print "<option value='" . $row["city"] . "'>" . $row["city"] . "</option>\n\r";
                        } // END FOREACH

                        // END FORM
                        print "</select>\n\r";
                        print "<input type='submit' value='Display Cities'>\n\r";
                        print "</form>\n\r\n\r";

                        // BEGIN ALL CITIES FORM
                        print "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>\n\r";
                        print "<input type='submit' name='clear' value='Display ALL Cities'>\n\r";
                        print "</form>\n\r\n\r";

                    } else {
                        // MESSAGE FOR NO RESULTS
                        print "Sorry, there were no results.";
                    } // END IF ELSE STATEMENT
                ?>
            <div id="tborder">
                <?php
                    // CHECK TO MAKE SURE WE HAVE RECORDS RETURNED
                    if ($rowcount != 0) {
            
                        // HEADER ROW OF TABLE
                        print "\n\r";
                        print "\t\t\t\t<table>\n\r";
                        print "\t\t\t\t\t<tr>\n\r";
                        print "\t\t\t\t\t\t<th>Last Name</th>\n\r";
                        print "\t\t\t\t\t\t<th>First Name</th>\n\r";
                        print "\t\t\t\t\t\t<th>Address</th>\n\r";
                        print "\t\t\t\t\t\t<th>City</th>\n\r";
                        print "\t\t\t\t\t\t<th>Postal Code</th>\n\r";
                        print "\t\t\t\t\t\t<th>Phone</th>\n\r";
                        print "\t\t\t\t\t\t<th id=\"email\">Email</th>\n\r";
                        print "\t\t\t\t\t</tr>\n\r\n\r";
            
                        // DISPLAY ROWS FROM RESULTS
                        $rows = $statement->fetchAll();
            
                        // BODY OF TABLE
                        foreach ($rows as $row) {
            
                            print "\t\t\t\t\t<tr>\n\r";
                            print "\t\t\t\t\t\t<td>" . $row["lname"] . "</td>\n\r";
                            print "\t\t\t\t\t\t<td class=\"lborder\">" . $row["fname"] . "</td>\n\r";
                            print "\t\t\t\t\t\t<td class=\"lborder\">" . $row["address"] . "</td>\n\r";
                            print "\t\t\t\t\t\t<td class=\"lborder\">" . $row["city"] . "</td>\n\r";
                            print "\t\t\t\t\t\t<td class=\"lborder\">" . $row["postal_code"] . "</td>\n\r";
                            print "\t\t\t\t\t\t<td class=\"lborder\">" . $row["phone"] . "</td>\n\r";
                            print "\t\t\t\t\t\t<td class=\"lborder\">" . $row["email"] . "</td>\n\r";
                            print "\t\t\t\t\t</tr>\n\r\n\r";
                        } // END FOREACH
            
                        // END TABLE
                        print "\t\t\t\t</table>\n\r";
            
                    } else {
                        
                        // ERROR MESSAGE
                        print "Sorry, there were no results";
            
                    } // END IF-ELSE 
            
                    // CLOSE CONNECTION
                    $conn = null;
                ?>
            </div>
        </div>
    </body>
</html>