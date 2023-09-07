<?php
define("DATA_FILE", "data.txt");
define("INDEX_FILE", "index.txt");

class Patient {
    public function modify($oldPid, $newPid, $name, $gender, $age, $bloodGroup, $phone) {
        $data = file(DATA_FILE, FILE_IGNORE_NEW_LINES);
        $indexData = file(INDEX_FILE, FILE_IGNORE_NEW_LINES);
        $updatedData = [];
        $updatedIndexData = [];

        $found = false;
        $existingPid = false;

        foreach ($data as $lineNumber => $line) {
            $fields = explode("|", $line);
            $currentPID = isset($fields[0]) ? $fields[0] : '';

            if ($currentPID === $oldPid) {
                $found = true;
                // Check if new PID already exists
                if ($newPid !== $oldPid && $this->getRecord($newPid) !== null) {
                    $existingPid = true;
                    break;
                }
                $updatedData[] = "$newPid|$name|$gender|$age|$bloodGroup|$phone";
                $updatedIndexData[] = "$newPid|" . strlen($updatedData[count($updatedData) - 1]);
            } else {
                $updatedData[] = $line;
                $updatedIndexData[] = isset($indexData[$lineNumber]) ? $indexData[$lineNumber] : '';
            }
        }

        if ($existingPid) {
            echo "<p style='color:green'> Cannot update to PID $newPid. It already exists in the table.</p>";
            return;
        }

        if (!$found) {
            echo "<p style='color:green'> No record found with PID: $oldPid</p>";
            return;
        }

        file_put_contents(DATA_FILE, implode(PHP_EOL, $updatedData));
        file_put_contents(INDEX_FILE, implode(PHP_EOL, $updatedIndexData));

        echo "<p style='color:green'> Record with PID: $oldPid has been updated to $newPid successfully.</p>";
    }

    public function getRecord($pid) {
        $data = file(DATA_FILE, FILE_IGNORE_NEW_LINES);

        foreach ($data as $line) {
            $fields = explode("|", $line);
            $currentPID = isset($fields[0]) ? $fields[0] : '';

            if ($currentPID === $pid) {
                return $fields;
            }
        }

        return null;
    }
}

$patient = new Patient();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPid = $_POST['old_pid'] ?? '';
    $newPid = $_POST['pid'] ?? '';
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $age = $_POST['age'] ?? '';
    $bloodGroup = $_POST['bloodGroup'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $patient->modify($oldPid, $newPid, $name, $gender, $age, $bloodGroup, $phone);
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Modify Patient</title>
</head>
<body>
    <h1>Modify Patient</h1>
    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') { ?>
    <form method="POST" action="modify.php">
        <label for="pid">Patient ID:</label>
        <input type="text" name="pid" id="pid" required>
        <br>
        <input type="submit" value="Search Patient">
    </form>
    <?php } ?>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pid'])) {
        $record = $patient->getRecord($_POST['pid']);
        if ($record) {
            $oldPid = isset($record[0]) ? $record[0] : '';
            $name = isset($record[1]) ? $record[1] : '';
            $gender = isset($record[2]) ? $record[2] : '';
            $age = isset($record[3]) ? $record[3] : '';
            $bloodGroup = isset($record[4]) ? $record[4] : '';
            $phone = isset($record[5]) ? $record[5] : '';
            ?>
            <form method="POST" action="modify.php">
                <input type="hidden" name="old_pid" value="<?= $oldPid ?>">
                <label for="pid">Patient ID:</label>
                <input type="text" name="pid" id="pid" value="<?= $oldPid ?>" required>
                <br>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?= $name ?>" required>
                <br>
                <label for="gender">Gender:</label>
                <input type="text" name="gender" id="gender" value="<?= $gender ?>" required>
                <br>
                <label for="age">Age:</label>
                <input type="text" name="age" id="age" value="<?= $age ?>" required>
                <br>
                <label for="bloodGroup">Blood Group:</label>
                <input type="text" name="bloodGroup" id="bloodGroup" value="<?= $bloodGroup ?>" required>
                <br>
                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" value="<?= $phone ?>" required>
                <br>
                <input type="submit" value="Modify Record">
            </form>
        <?php } else { ?>
            <p>No record found with PID: <?= $_POST['pid'] ?></p>
        <?php }
    } ?>
    <p id="success-message" style="display: none; color: black;">Record submitted successfully!</p>
    <center>
        <div>
            <a href="add.php">Add</a>
            <a href="display.php">Display</a>
            <a href="modify.php">Modify</a>
            <a href="delete.php">Delete</a>
            <a href="search.php">Search</a>
        </div>
    </center>

</body>
</html>