<?php
define("datafile", "data.txt");
define("indexfile", "index.txt");

class Patient {
    public function add($pid, $name, $gender, $age, $bloodGroup, $phone) {
        $record = "$pid|$name|$gender|$age|$bloodGroup|$phone" . PHP_EOL;
        
        // Check if the record with the same ID already exists
        if ($this->isRecordExists($pid)) {
            echo "<p style='color:red'> Patient record with ID $pid already exists.</p>";
            return;
        }
        
        file_put_contents(datafile, $record, FILE_APPEND);
        
        $index = file(indexfile, FILE_IGNORE_NEW_LINES);
        $index[] = "$pid|" . strlen($record);
        sort($index);
        file_put_contents(indexfile, implode(PHP_EOL, $index));
        
        echo "<p style='color:green'> Patient record added successfully.</p>";
    }
    
    private function isRecordExists($pid) {
        $index = file(indexfile, FILE_IGNORE_NEW_LINES);
        foreach ($index as $line) {
            $record = explode("|", $line);
            if ($record[0] == $pid) {
                return true;
            }
        }
        return false;
    }
}

$patient = new Patient();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['pid'] ?? '';
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $age = $_POST['age'] ?? '';
    $bloodGroup = $_POST['bloodGroup'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $patient->add($pid, $name, $gender, $age, $bloodGroup, $phone);
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <script>
        function handleSubmit(event) {
            event.preventDefault(); // Prevent form submission

            // Add your code here to handle form submission, e.g., sending data to server using AJAX

            // Display success message
            var successMessage = document.getElementById("success-message");
            successMessage.style.display = "block";
        }
    </script>
    <title>Add Patient</title>
</head>
<body>
    <div>
    <h1>Add Patient</h1>
    <form method="POST" action="add.php">
        <label for="pid">Patient ID:</label>
        <input type="text" name="pid" id="pid" required>
        <br>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="gender">Gender:</label>
        <input type="text" name="gender" id="gender" required>
        <br>
        <label for="age">Age:</label>
        <input type="text" name="age" id="age" required>
        <br>
        <label for="bloodGroup">Blood Group:</label>
        <input type="text" name="bloodGroup" id="bloodGroup" required>
        <br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" required>
        <br>
        <input type="submit" value="Submit">
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