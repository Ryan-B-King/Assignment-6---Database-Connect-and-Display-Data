<?php

    // SETUP TO DATABASE
    $dsn = "mysql:host=localhost;dbname=college";
    $username = "root";
    $password = "";

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
    $sql = "SELECT lname, fname, address, postal_code, phone, email
            FROM college.members
            WHERE city = 'San Diego'
            ORDER BY postal_code, lname";
    $statement = $conn->prepare($sql);

    // EXECUTE RESULT SET
    $statement->execute();

    // ROW COUNT
    $rowcount = $statement->rowCount();

    // TEST ROW COUNT
    // print "Row count is " . $rowcount;

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

        </style>
    </head>
    <body>
        <div>
            <h1>Student Population by City of Residence</h1>
            <h2><?php print $rowcount . " Students Living in San Diego"?></h2>
            <div id="tborder">
                <?php
        
                if ($rowcount != 0) {
        
                    // HEADER ROW OF TABLE
                    print "\n\r";
                    print "<table>\n\r";
                    print "<tr>\n\r";
                    print "<th>Last Name</th>\n\r";
                    print "<th>First Name</th>\n\r";
                    print "<th>Address</th>\n\r";
                    print "<th>Postal Code</th>\n\r";
                    print "<th>Phone</th>\n\r";
                    print "<th id=\"email\">Email</th>\n\r";
                    print "</tr>\n\r\n\r";
        
                    // DISPLAY ROWS FROM RESULTS
                    $rows = $statement->fetchAll();
        
                    // BODY OF TABLE
                    foreach ($rows as $row) {
        
                        print "<tr>\n\r";
                        print "<td>" . $row["lname"] . "</td>\n\r";
                        print "<td class=\"lborder\">" . $row["fname"] . "</td>\n\r";
                        print "<td class=\"lborder\">" . $row["address"] . "</td>\n\r";
                        print "<td class=\"lborder\">" . $row["postal_code"] . "</td>\n\r";
                        print "<td class=\"lborder\">" . $row["phone"] . "</td>\n\r";
                        print "<td class=\"lborder\">" . $row["email"] . "</td>\n\r";
                        print "</tr>\n\r\n\r";
                    } // END FOREACH
        
                    // END TABLE
                    print "</table>\n\r";
        
                } else {
        
                    print "Sorry, there were no results";
        
                } // END IF-ELSE 
        
                // CLOSE CONNECTION
                $conn = null;
        
                ?>
            </div>
        </div>
    </body>
</html>