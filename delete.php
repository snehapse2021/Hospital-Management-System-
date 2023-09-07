<?php
define("datafile", "data.txt");
define("indexfile", "index.txt");

class Patient {
    public function delete($pid) {
        $data = file(datafile, FILE_IGNORE_NEW_LINES);
        $index = file(indexfile, FILE_IGNORE_NEW_LINES);

        $found = false;
        $updatedData = [];
        foreach ($data as $record) {
            $fields = explode("|", $record);
            $currentPid = $fields[0] ?? '';
            if ($currentPid === $pid) {
                $found = true;
              $record = '$' . $record; // Mark the record as deleted
            }
            $updatedData[] = $record;
        }

        if ($found) {
            $updatedIndex = [];
            foreach ($index as $line) {
                list($ipid, $offset) = explode("|", $line);
                if ($ipid !== $pid) {
                    $updatedIndex[] = $line;
                }
            }

            file_put_contents(datafile, implode(PHP_EOL, $updatedData));
            file_put_contents(indexfile, implode(PHP_EOL, $updatedIndex));

            echo "<p style='color:green'>Record deleted successfully.</p>";
        } else {
            echo "<p style='color:red'>Record not found.</p>";
        }
    }
}

$patient = new Patient();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['pid'] ?? '';
    $patient->delete($pid);
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Delete Patient</title>
</head>
<body>
    <h1>Delete Patient</h1>
    <form method="POST" action="delete.php">
        <label for="pid">Patient ID:</label>
        <input type="text" name="pid" id="pid" required>
        <br>
        <input type="submit" value="Delete Patient">
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


