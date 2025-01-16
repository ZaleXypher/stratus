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

pg_query($conn, "CREATE OR REPLACE FUNCTION create_table(text) RETURNS VOID AS $$
BEGIN
    EXECUTE 'CREATE TABLE IF NOT EXISTS presence.' || quote_ident($1) || ' (kelas TEXT, absen INT, nama TEXT, id varchar);';
END
$$ LANGUAGE plpgsql;");
pg_query($conn, "CREATE OR REPLACE FUNCTION generate_hash(text) RETURNS VOID AS $$
DECLARE
    id TEXT;
BEGIN
    FOR id IN EXECUTE 'SELECT md5(nama) FROM presence.' || quote_ident($1)
    LOOP
        EXECUTE 'UPDATE presence.' || quote_ident($1) || ' SET id = ''' || substr(id, 1, 16) || ''' WHERE nama = (SELECT nama FROM presence.' || quote_ident($1) || ' WHERE md5(nama) = ''' || id || ''');';
    END LOOP;
END
$$ LANGUAGE plpgsql;");

pg_query($conn, "CREATE OR REPLACE FUNCTION countrow(text) RETURNS VOID AS $$
    DECLARE
row_count INT;
BEGIN
    EXECUTE 'SELECT COUNT(*) FROM presence.' || quote_ident($1) || ';' INTO row_count;
RAISE NOTICE 'has % rows.', row_count;
END;
$$ LANGUAGE plpgsql;");
$result = pg_query_params($conn, 'SELECT countrow($1)', array($month));
preg_match('/has (.*) rows./', pg_last_notice($conn), $rowCount);

pg_query($conn, "CREATE OR REPLACE FUNCTION syncnames(text) RETURNS VOID AS $$
BEGIN
    EXECUTE 'INSERT INTO presence.' || quote_ident($1) || '(kelas, absen, nama, id) SELECT kelas,absen,nama,id FROM presence.studentlist;';
END;
$$ LANGUAGE plpgsql;");

pg_query($conn, "CREATE OR REPLACE FUNCTION add_column(text, text) RETURNS VOID AS $$
BEGIN   
    EXECUTE 'ALTER TABLE presence.' || quote_ident($1) ||' ADD COLUMN IF NOT EXISTS ' || quote_ident($2) || 'TEXT';
END;
$$ LANGUAGE plpgsql;");
pg_query($conn, "CREATE OR REPLACE FUNCTION checkpresence(text, text) RETURNS VOID AS $$
BEGIN
    EXECUTE 'INSERT INTO presence.presencelist (kelas, absen, nama, id, kehadiran) SELECT kelas,absen,nama,id,' || quote_ident($1) || ' FROM presence.' || quote_ident($2) || ';';
END;
$$ LANGUAGE plpgsql;");

pg_query($conn, "CREATE OR REPLACE FUNCTION generate_hash(text) RETURNS VOID AS $$
DECLARE
    id TEXT;
BEGIN
    FOR id IN EXECUTE 'SELECT md5(nama) FROM presence.' || quote_ident($1)
    LOOP
        EXECUTE 'UPDATE presence.' || quote_ident($1) || ' SET id = ''' || substr(id, 1, 16) || ''' WHERE nama = (SELECT nama FROM presence.' || quote_ident($1) || ' WHERE md5(nama) = ''' || id || ''');';
    END LOOP;
END
$$ LANGUAGE plpgsql;");

pg_query($conn, "CREATE OR REPLACE FUNCTION countrow(text) RETURNS VOID AS $$
    DECLARE
row_count INT;
BEGIN
    EXECUTE 'SELECT COUNT(*) FROM presence.' || quote_ident($1) || ';' INTO row_count;
RAISE NOTICE 'has % rows.', row_count;
END;
$$ LANGUAGE plpgsql;");
$result = pg_query_params($conn, 'SELECT countrow($1)', array($month));
preg_match('/has (.*) rows./', pg_last_notice($conn), $rowCount);

pg_query($conn, "CREATE OR REPLACE FUNCTION syncnames(text) RETURNS VOID AS $$
BEGIN
    EXECUTE 'INSERT INTO presence.' || quote_ident($1) || '(kelas, absen, nama, id) SELECT kelas,absen,nama,id FROM presence.studentlist;';
END;
$$ LANGUAGE plpgsql;");

pg_query($conn, "CREATE OR REPLACE FUNCTION add_column(text, text) RETURNS VOID AS $$
BEGIN   
    EXECUTE 'ALTER TABLE presence.' || quote_ident($1) ||' ADD COLUMN IF NOT EXISTS ' || quote_ident($2) || 'TEXT';
END;
$$ LANGUAGE plpgsql;");

pg_query($conn, "CREATE OR REPLACE FUNCTION checkpresence(text, text) RETURNS VOID AS $$
BEGIN
    EXECUTE 'INSERT INTO presence.presencelist (kelas, absen, nama, id, kehadiran) SELECT kelas,absen,nama,id,' || quote_ident($1) || ' FROM presence.' || quote_ident($2) || ';';
END;
$$ LANGUAGE plpgsql;");

pg_query_params($conn,'SELECT create_table($1)', array($month));
pg_query_params($conn, 'SELECT generate_hash($1)', array('studentlist'));
pg_query_params($conn,'SELECT add_column($1, $2)', array($month, $day));
if ($rowCount[1] == 0) {
    pg_query_params($conn, 'SELECT syncnames($1)', array($month));
}
pg_query($conn, 'DROP TABLE IF EXISTS presence.presencelist CASCADE;');
pg_query($conn,'CREATE TABLE IF NOT EXISTS presence.presencelist (kelas TEXT, absen INT, nama TEXT, id varchar, kehadiran TEXT);');
pg_query_params($conn, 'SELECT checkpresence($1, $2)', array($day, $month));
pg_query($conn, "UPDATE presence.presencelist SET kehadiran = 'Belum Terdata' WHERE kehadiran IS NULL");

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