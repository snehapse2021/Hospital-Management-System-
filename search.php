<?php
define("datafile", "data.txt");

class Patient {
    public function search($pid) {
        $handle = fopen(datafile, "r");
        while (($record = fgets($handle)) !== false) {
            $recordData = explode("|", trim($record));
            if ($recordData[0] == $pid) {
                $name = $recordData[1];
                $gender = $recordData[2];
                $age = $recordData[3];
                $bloodGroup = $recordData[4];
                $phone = $recordData[5];

                echo '<h2>Search Result</h2>';
                echo '<table style="border-collapse:collapse;border-style: solid;width:100%;border-color: rgb(39, 24, 81);" border="5px">';
                echo '<tr><th>Patient ID</th><th>Name</th><th>Gender</th><th>Age</th><th>Blood Group</th><th>Phone</th></tr>';
                echo "<tr><td>$pid</td><td>$name</td><td>$gender</td><td>$age</td><td>$bloodGroup</td><td>$phone</td></tr>";
                echo '</table>';

                fclose($handle);
                return;
            }
        }

        fclose($handle);
        echo "<p style=color:red;>Record not found.</p>";
    }
}

$patient = new Patient();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['pid'] ?? '';
    $patient->search($pid);
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Search Patient</title>
</head>
<body>
    <h1>Search Patient</h1>
    <form method="POST" action="search.php">
        <label for="pid">Patient ID:</label>
        <input type="text" name="pid" id="pid" required>
        <br>
        <input type="submit" value="Search Patient">
    </form>
    <center>
        <div>
            <p id="success-message" style="display: none; color: black;">Record submitted successfully!</p>
            <a href="add.php">Add</a>
            <a href="display.php">Display</a>
            <a href="modify.php">Modify</a>
            <a href="delete.php">Delete</a>
            <a href="search.php">Search</a>
        </div>  
    </center> 
    </body> 
</html>
