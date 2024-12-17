<!DOCTYPE html>
<html>
    <head>
        <title>Stratus</title>
        <script type="text/javascript">
            function closeTab() {
                window.close();
            }
            window.onload = function() {
                setTimeout(closeTab, 50);
            };
        </script>

    </head>
        <?php
            $config = include('config/config.php');
            $host = $config['host'];
            $port = $config['port'];
            $dbname = $config['dbname'];
            $user = $config['user'];
            $password = $config['password'];

            $conn = pg_connect("host=$host dbname=$dbname user=$user password=xiid");
            $stat = pg_connection_status($conn);
            
            $today = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
            $month = strtolower($today->format('F_Y'));
            $day = strtolower($today->format('d'));

            pg_query($conn, "CREATE OR REPLACE FUNCTION checkpresence(text, text) RETURNS VOID AS $$
            BEGIN
                EXECUTE 'INSERT INTO presence.presencelist (kelas, absen, nama, id, kehadiran) SELECT kelas,absen,nama,id,' || quote_ident($1) || ' FROM presence.' || quote_ident($2) || ';';
            END;
            $$ LANGUAGE plpgsql;");

            pg_query($conn, 'DROP TABLE IF EXISTS presence.presencelist CASCADE;');
            pg_query($conn,'CREATE TABLE IF NOT EXISTS presence.presencelist (kelas TEXT, absen INT, nama TEXT, id varchar, kehadiran TEXT);');
            pg_query_params($conn, 'SELECT checkpresence($1, $2)', array($day, $month));
            pg_query($conn, "UPDATE presence.presencelist SET kehadiran = 'Belum Terdata' WHERE kehadiran IS NULL");
        ?>
    </body>
</html>