<!DOCTYPE html>
<html>
    <title>Stratus</title>
    <script>
        var referrer = document.referrer;
        if(referrer) {
            window.location.href = referrer;
        }    
        else {
            window.location.href = '/daftarkelas/daftartelatmaster.php'
        }
    </script>
    
    <body>
        <?php
            //DB INFO
            $user = 'strada';
            $host = 'localhost';
            $port = 5432;
            $dbname = 'strada';
            $pres = 'presence';
            $stulist = 'studentlist';

            //CONNECTING TO THE DB
            $conn = pg_connect("host=$host dbname=$dbname user=$user password=xiid");
            $stat = pg_connection_status($conn);
            

            //TABLE CREATION BASED ON DATE
            $today = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
            $month = strtolower($today->format('F_Y'));
            $day = strtolower($today->format('d'));
            $clock = strtolower($today->format('H:i:s'));

            pg_query($conn, "CREATE OR REPLACE FUNCTION create_table(text, text) RETURNS VOID AS $$
            BEGIN
                EXECUTE 'CREATE TABLE IF NOT EXISTS ' || quote_ident($1) || '.' || quote_ident($2) || ' (kelas TEXT, absen INT, nama TEXT, id varchar);';
            END
            $$ LANGUAGE plpgsql;");
            
            pg_query_params($conn,'SELECT create_table($1, $2)', array($pres, $month));

            //AUTO-HASH
            //HACK:AI GENERATED
            pg_query($conn, "CREATE OR REPLACE FUNCTION generate_hash(text) RETURNS VOID AS $$
            DECLARE
                id TEXT;
            BEGIN
                FOR id IN EXECUTE 'SELECT md5(nama) FROM presence.' || quote_ident($1)
                LOOP
                    EXECUTE 'UPDATE presence.' || quote_ident($1) || ' SET id = ''' || id || ''' WHERE nama = (SELECT nama FROM presence.' || quote_ident($1) || ' WHERE md5(nama) = ''' || id || ''');';
                END LOOP;
            END
            $$ LANGUAGE plpgsql;");
            
            pg_query_params($conn, 'SELECT generate_hash($1)', array($stulist));
            pg_query_params($conn, 'SELECT generate_hash($1)', array($month));

            //STUDENT LIST
            
            //GET ROW COUNT
            //HACK:AI GENERATED, ONLY SLIGHTLY CHANGED
            pg_query($conn, "CREATE OR REPLACE FUNCTION countrow(text, text) RETURNS VOID AS $$
                DECLARE
            row_count INT;
            BEGIN
                EXECUTE 'SELECT COUNT(*) FROM ' || quote_ident($1) || '.' || quote_ident($2) || ';' INTO row_count;
            RAISE NOTICE 'has % rows.', row_count;
            END;
            $$ LANGUAGE plpgsql;");
            $result = pg_query_params($conn, 'SELECT countrow($1, $2)', array($pres, $month));
            preg_match('/has (.*) rows./', pg_last_notice($conn), $rowCount);

            //SYNC FUNCTION

            pg_query($conn, "CREATE OR REPLACE FUNCTION syncnames(text, text, text) RETURNS VOID AS $$
            BEGIN
                EXECUTE 'INSERT INTO ' || quote_ident($1) || '.' || quote_ident($2) || '(kelas, absen, nama, id) SELECT kelas,absen,nama,id FROM ' || quote_ident($1) || '.' || quote_ident($3) || ';';
            END;
            $$ LANGUAGE plpgsql;");
            
            if ($rowCount[1] == 0) {
                pg_query_params($conn, 'SELECT syncnames($1, $2, $3)', array($pres, $month, $stulist));
            }
           
            //AUTOMATIC COLUMN CREATION BASED ON DATE

            pg_query($conn, "CREATE OR REPLACE FUNCTION add_column(text, text, text) RETURNS VOID AS $$
            BEGIN   
                EXECUTE 'ALTER TABLE ' || quote_ident($1) || '.' || quote_ident($2) ||' ADD COLUMN IF NOT EXISTS ' || quote_ident($3) || 'TEXT';
            END;
            $$ LANGUAGE plpgsql;");

            pg_query_params($conn,'SELECT add_column($1, $2, $3)', array($pres, $month, $day));
            
            //CREATE TARDY LIST
            pg_query($conn, "CREATE OR REPLACE FUNCTION droplate(text) RETURNS VOID AS $$
            BEGIN
                EXECUTE 'DROP TABLE IF EXISTS ' || quote_ident($1) || '.latelist CASCADE;';
            END;
            $$ LANGUAGE plpgsql;");
            
            pg_query($conn, "CREATE OR REPLACE FUNCTION checklate(text, text, text) RETURNS VOID AS $$
            BEGIN
                EXECUTE 'INSERT INTO ' || quote_ident($1) || '.latelist (kelas, absen, nama, id) SELECT kelas,absen,nama,id FROM ' || quote_ident($1) || '.' || quote_ident($2) || ' WHERE ' || quote_ident($3) || ' IS NULL;';
            END;
            $$ LANGUAGE plpgsql;");
                
                
                
            pg_query_params($conn, 'SELECT droplate($1)', array($pres));
            pg_query_params($conn,'SELECT create_table($1, $2)', array($pres, 'latelist'));
            pg_query_params($conn, 'SELECT checklate($1, $2, $3)', array($pres, $month, $day));


            //INTERFACE WITH HTTP REQUESTS
            
            //ATTENDANCE STATUSES:
            //1 = PRESENT
            //2 = LATE
            //3 = SICK
            //4 = OTHERS
            if(isset($_GET['presenceid']) && $_GET['attendancestatus'] == 1) {
                $presenceid = $_GET['presenceid'];
                pg_query($conn, "UPDATE $pres.$month SET \"$day\" = '$clock' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
            }             
            elseif(isset($_GET['presenceid']) && $_GET['attendancestatus'] == 2){
                $presenceid = $_GET['presenceid'];
                pg_query($conn, "UPDATE $pres.$month SET \"$day\" = 'Telat' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
            }
            elseif(isset($_GET['presenceid']) && $_GET['attendancestatus'] == 3){
                $presenceid = $_GET['presenceid'];
                pg_query($conn, "UPDATE $pres.$month SET \"$day\" = 'Sakit' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
            }
            elseif(isset($_GET['presenceid']) && $_GET['attendancestatus'] == 4){
                $presenceid = $_GET['presenceid'];
                pg_query($conn, "UPDATE $pres.$month SET \"$day\" = 'Izin' WHERE \"id\" = '$presenceid' AND \"$day\" IS NULL");
            }
            elseif($clock>0){
                pg_query($conn, "UPDATE $pres.$month SET \"$day\" = 'Tidak Hadir' WHERE \"$day\" IS NULL");
            }
            else{
            }
        ?>
    </body>
</html>