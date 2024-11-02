SELECT 
    'shell' AS component, 
    'Stratus' AS title,
    '1' AS sidebar,
    'dark' AS theme,
    JSON('{"title":"Daftar Kelas","submenu":[{"link":"/daftarmurid/xiid.sql","title":"XII-D"}]}') as menu_item;
SELECT 'button' AS component, 'center' AS justify;
SELECT 'Alasan Tidak Hadir' AS title, 'http://localhost:8090/daftarkelas/daftartelatxiid.php' as link;
SELECT 'list' AS component, 'Daftar Tidak Hadir Kelas XII-D' AS title;
SELECT nama AS title FROM presence.latelist WHERE kelas = 'XII-D';