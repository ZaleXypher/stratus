--REPLACE PLACEHOLDER AND PLACEKELAS
SELECT 
    'shell' AS component, 
    'Stratus' AS title,
    '1' AS sidebar,
    'dark' AS theme,
    JSON(PLACEHOLDER) as menu_item; 
SELECT 'shell' AS component, 'placekelas' AS title;
SELECT 'button' AS component, 'center' AS justify;
SELECT 'Alasan Tidak Hadir' AS title, 'http://0.0.0.0:8090/daftarkelas/daftartelatplacekelas.php' as link;
SELECT 'list' AS component, 'Daftar Kehadiran Kelas placekelas' AS title;
SELECT nama AS title FROM presence.presencelist WHERE kelas = 'placekelas' ; 