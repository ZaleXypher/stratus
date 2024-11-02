<?php
// Database connection parameters
$host = "localhost";
$port = "5432";
$dbname = "strada";
$user = "strada";
$password = "xiid";

// Establish a connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Query to fetch names
$query = 'SELECT nama, id FROM presence.latelist WHERE kelas = \'XII-D\'';
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Stratus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Telat XII-D</title>
    <link rel="stylesheet" href="formstyle.css">
</head>
<body>
    <form action="/presenceapi.php" method="GET">
        <label for="names">Daftar Nama</label>
        <select name="presenceid" id="name">
            <option value="refresh">Refresh</option>
            <?php
            // Fetch and display names in the dropdown
            while ($row = pg_fetch_assoc($result)) {
                echo "<option value=\"" . htmlspecialchars($row['id']) . "\">" . htmlspecialchars($row['nama']) . "</option>";
            }

            // Free the result set
            pg_free_result($result);
            ?>
        </select>
        <label for="status">Alasan</label>
        <select name="attendancestatus" id="status">
            <option value="1">Hadir</option>
            <option value="2">Telat</option>
            <option value="3">Sakit</option>
            <option value="4">Izin</option>
        </select>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
// Close the connection
pg_close($conn);
?>