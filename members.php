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
        print "Connection is successful<br><br>";
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
    print "Row count is " . $rowcount;

?>

<!DOCTYPE html><!-- Ryan King -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Population</title>
    <style>

    </style>
</head>
<body>

    <?php

    if ($rowcount != 0) {

        // HEADER ROW OF TABLE
        print "\n\r";
        print "<table>\n\r";
        print "<tr>\n\r";
        print "<th>Last Name</th>\n\r";
        print "<th>First Name</th>\n\r";
        print "<th>Addres</th>\n\r";
        print "<th>Postal Code</th>\n\r";
        print "<th>Phone</th>\n\r";
        print "<th>Email</th>\n\r";
        print "</tr>\n\r\n\r";

        // DISPLAY ROWS FROM RESULTS
        $rows = $statement->fetchAll();

        // BODY OF TABLE
        foreach ($rows as $row) {

            print "<tr>\n\r";
            print "<td>" . $row["lname"] . "</td>\n\r";
            print "<td>" . $row["fname"] . "</td>\n\r";
            print "<td>" . $row["address"] . "</td>\n\r";
            print "<td>" . $row["postal_code"] . "</td>\n\r";
            print "<td>" . $row["phone"] . "</td>\n\r";
            print "<td>" . $row["email"] . "</td>\n\r";
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


</body>
</html>