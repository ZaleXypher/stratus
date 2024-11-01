<!DOCTYPE html>
<html>
    <body>
        <?php
            //DB INFO
            $user = 'strada';
            $host = 'localhost';
            $port = 54321;
            $dbname = 'strada';
            $kelas = 'xii_d';
            $pres = 'presence';
            $stulist = 'studentlist';

            //CONNECTING TO THE DB
            $conn = pg_connect("host=$host dbname=$dbname user=$user password=xiid");
            $stat = pg_connection_status($conn);
            if ($stat === PGSQL_CONNECTION_OK) {
                echo 'Main DB ok' ."\n";
            } else {
                echo 'Main DB bad' ."\n";
            }
            

            //TABLE CREATION BASED ON DATE
            $today = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
            $month = strtolower($today->format('F_Y'));
            $day = strtolower($today->format('d'));
            $clock = strtolower($today->format('H:i:s'));

            pg_query($conn, "CREATE OR REPLACE FUNCTION create_table(text, text) RETURNS VOID AS $$
            BEGIN
                EXECUTE 'CREATE TABLE IF NOT EXISTS ' || quote_ident($1) || '.' || quote_ident($2) || ' (absen INT, nama TEXT, id varchar);';
            END
            $$ LANGUAGE plpgsql;");
            
            pg_query_params($conn,'SELECT create_table($1, $2)', array($pres, $month));

            //AUTO-HASH
            pg_query($conn, "CREATE OR REPLACE FUNCTION generate_hash(text, text) RETURNS VOID AS $$
            DECLARE
                id TEXT;
            BEGIN
                SELECT md5(nama) INTO id FROM presence.November_2024 LIMIT 1;
                IF id IS NOT NULL THEN
                    EXECUTE 'ALTER TABLE ' || quote_ident($1) || '.' || quote_ident($2) || ' ADD COLUMN IF NOT EXISTS id TEXT UNIQUE DEFAULT ''' || id || ''';';
                    EXECUTE 'UPDATE ' || quote_ident($1) || '.' || quote_ident($2) || ' SET id = md5(nama);';
                END IF;
            END
            $$ LANGUAGE plpgsql;");

            pg_query_params($conn, 'SELECT generate_hash($1, $2)', array('presence', $month));

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

            pg_query($conn, "CREATE OR REPLACE FUNCTION syncnames(text, text, text, text) RETURNS VOID AS $$
            BEGIN
                EXECUTE 'INSERT INTO ' || quote_ident($1) || '.' || quote_ident($2) || '(absen, nama) SELECT absen,nama FROM ' || quote_ident($3) || '.' || quote_ident($4) || ';';
            END;
            $$ LANGUAGE plpgsql;");
            
            if ($rowCount[1] == 0) {
                pg_query_params($conn, 'SELECT syncnames($1, $2, $3, $4)', array($pres, $month, $stulist,  $kelas));
            }
           
            //COLUMN CREATION BASED ON DATE

            pg_query($conn, "CREATE OR REPLACE FUNCTION add_column(text, text, text) RETURNS VOID AS $$
            BEGIN   
                EXECUTE 'ALTER TABLE ' || quote_ident($1) || '.' || quote_ident($2) ||' ADD COLUMN IF NOT EXISTS ' || quote_ident($3) || 'TEXT';
            END;
            $$ LANGUAGE plpgsql;");

            pg_query_params($conn,'SELECT add_column($1, $2, $3)', array($pres, $month, $day));

            /*
            //TODO:INTERFACE WITH HTTP REQUESTS
            //FIXME:VSCODE DOES NOT LIKE URL QUERIES
            if(isset($_GET['presenceid'])) {
                $presenceid = $_GET['presenceid'];
                echo 'uid available'; 
            }  
            else{
                echo 'nouid';
            }
            */
            
            //DATA ENTERING
            $PLACEHOLDERID = 'd596ed1b76e8068a591dae9fd7d75d8b';
            pg_query($conn, "UPDATE $pres.$month SET \"$day\" = '$clock' WHERE \"id\" = '$PLACEHOLDERID' AND \"$day\" IS NULL")
        ?>
    </body>
</html>