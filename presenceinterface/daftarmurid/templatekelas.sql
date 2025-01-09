--REPLACE PLACEHOLDER AND PLACEKELAS
SELECT 
    'shell' AS component, 
    'Stratus' AS title,
    '1' AS sidebar,
    'dark' AS theme,
    JSON(PLACEHOLDER) as menu_item; 
SELECT 'shell' AS component, 'placekelas' AS title;
SELECT 'button' AS component, 'center' AS justify;
SELECT 'Daftar Tidak Hadir' AS title, '/daftarmurid/placekelas.sql' as link;
SELECT 'list' AS component, 'Daftar Telat Kelas placekelas' AS title;
SELECT nama AS title FROM presence.presencelist WHERE kelas = 'placekelas' ORDER BY absen; 