<?php
$config = include('config/config.php');
$host = $config['host'];
$port = $config['port'];
$dbname = $config['dbname'];
$user = $config['user'];
$password = $config['password'];

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");
$stat = pg_connection_status($conn);

$today = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
$month = strtolower($today->format('F_Y'));
$day = strtolower($today->format('d'));
$clock = strtolower($today->format('H:i:s'));

// Your function definitions and table creation logic here...

if (isset($_GET['presenceid']) || isset($_GET['hexid'])) {
    if (isset($_GET['presenceid'])) {
        $presenceid = $_GET['presenceid'];
    }
    if (isset($_GET['hexid'])) {
        $hexid = $_GET['hexid'];
        $presenceid = hex2bin($hexid);
    }
    
    if ($_GET['attendancestatus'] == 1) {
        $absensi = pg_query($conn, "UPDATE presence.$month SET \"$day\" = '$clock' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
        $namatabel = pg_fetch_assoc(pg_query($conn, "SELECT nama FROM presence.$month WHERE \"id\" = '$presenceid'"));
        $nama = $namatabel['nama'];
        if (pg_affected_rows($absensi) > 0) {
            header('Content-type: application/json');
            echo json_encode(['status' => 'Success', 'message' => 'Attendance updated', 'nama' => $nama]);
        } else {
            header('Content-type: application/json');
            echo json_encode(['status' => 'Error', 'message' => 'Attendance not updated']);
        }
    } elseif ($_GET['attendancestatus'] == 2) {
        pg_query($conn, "UPDATE presence.$month SET \"$day\" = 'Telat' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
    } elseif ($_GET['attendancestatus'] == 3) {
        pg_query($conn, "UPDATE presence.$month SET \"$day\" = 'Sakit' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
    } elseif ($_GET['attendancestatus'] == 4) {
        pg_query($conn, "UPDATE presence.$month SET \"$day\" = 'Izin' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
    }
    exit; // Ensure no further output is sent
}

// If you want to keep the HTML part for other purposes, you can separate it
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stratus</title>
    <script>
        var referrer = document.referrer;
        if(referrer) {
            window.location.href = referrer;
        }    
    </script>
</head>
<body>
</body>
</html>