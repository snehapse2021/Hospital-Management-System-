<?php
define("datafile", "data.txt");
define("indexfile", "index.txt");

class Student {
    public function dataDisp() {
        echo '<table style="border-collapse:collapse;border-style: solid;width:100%;border-color: rgb(39, 24, 81);"border="5px">';
        echo '<tr><th>PID</th><th>Name</th><th>Gender</th><th>Age</th><th>Blood Group</th><th>Phone</th></tr>';
        $file = fopen(datafile, "r");
        if ($file) {
            while (!feof($file)) {
                $record = fgets($file);
                if (!empty($record)) {
                    $fields = explode("|", $record);
                    $pid = $fields[0] ?? '';
                    $name = $fields[1] ?? '';
                    $gender = $fields[2] ?? '';
                    $age = $fields[3] ?? '';
                    $bloodGroup = $fields[4] ?? '';
                    $phone = $fields[5] ?? '';
                    
                   if (isset($pid[0])) {
                       if ($pid[0] !== '$') {
                           echo "<tr><td>$pid</td><td>$name</td><td>$gender</td><td>$age</td><td>$bloodGroup</td><td>$phone</td></tr>"; 
                        } else {
                           $pid = substr($pid, 1); // Remove the '$' symbol from the PID
                           echo "<span style='color:red;'><tr style='color:red;'><td>$$pid</td><td>$name</td><td>$gender</td><td>$age</td><td>$bloodGroup</td><td>$phone</td></tr></span>";
                        }
                    }
                }
            }
            fclose($file);
        } else {
            echo "Failed to open data file: " . datafile;
        }
        echo '</table>';
    }
    

    public function generateIndex() {
        $data = file(datafile, FILE_IGNORE_NEW_LINES);
        $indexData = [];
        $address = 0; // Initial byte offset is 0
    
        foreach ($data as $lineNumber => $line) {
            $fields = explode("|", $line);
            $pid = $fields[0] ?? '';
    
            // Skip empty or deleted records
            if (empty($pid) || $pid[0] === '$') {
                continue;
            }
    
            $indexData[$pid] = $address;
    
            // Calculate byte offset for the next record
            $address += strlen($line);
        }
    
        ksort($indexData); // Sort index data based on PID
    
        $indexFile = fopen(indexfile, "w");
        if ($indexFile) {
            foreach ($indexData as $pid => $address) {
                fwrite($indexFile, "$pid|$address" . PHP_EOL);
            }
            fclose($indexFile);
        } else {
            echo "Failed to open index file: " . indexfile;
        }
    
        return $indexData;
    }
    
    

    public function displayIndex() {
        echo '<h2>Index Details</h2>';
        echo '<table style="border-collapse:collapse;border-style: solid;width:100%;border-color: rgb(39, 24, 81);"border="5px">';
        echo '<tr><th>IPID</th><th>Address</th></tr>';

        $indexData = $this->generateIndex();

        foreach ($indexData as $pid => $address) {
            if (substr($pid, 0, 1) !== '$') {
                echo "<tr><td>$pid</td><td>$address</td></tr>";
            }
        }

        echo '</table>';
    }
}

$student = new Student();
?>
 

<!DOCTYPE html>
<html>
<head>
    <title>Display Records</title>
    <style>
        body {
          background-color: #f2f2f2;
          background-image:url(https://na3em.cc/wp-content/uploads/2020/05/5465-4.jpg);
          font-family: Arial, sans-serif;
        }
    
        h1 {
          color: #333;
          text-align: center;
        }
    
        form {
          max-width: 400px;
          margin: 20px auto;
          padding: 20px;
          background-color: #fff;
          border-radius: 5px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    
        label {
          display: block;
          margin-bottom: 10px;
          color: #333;
          font-weight: bold;
        }
    
        input[type="text"] {
          width: 100%;
          height: 40px;
          padding: 5px;
          font-size: 16px;
          border: 1px solid #ccc;
          border-radius: 4px;
        }
    
        input[type="submit"] {
          display: block;
          width: 100%;
          height: 40px;
          margin-top: 10px;
          font-size: 16px;
          color: #fff;
          background-color: #4CAF50;
          border: none;
          border-radius: 4px;
          cursor: pointer;
        }
    
        input[type="submit"]:hover {
          background-color: #45a049;
        }
      </style>
</head>
<body>
    <h1>Display Records</h1>
    <?php $student->dataDisp(); ?>
    <?php $student->displayIndex(); ?>
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
