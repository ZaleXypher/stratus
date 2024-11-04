--REPLACE PLACEHOLDER AND PLACEKELAS
SELECT 
    'shell' AS component, 
    'XII-G' AS title,
    '1' AS sidebar,
    'dark' AS theme,
    JSON('{"title":"Daftar Kelas","submenu":
    [{"link":"/daftarmurid/xiia.sql","title":"XII-A"},
    {"link":"/daftarmurid/xiib.sql","title":"XII-B"},
    {"link":"/daftarmurid/xiic.sql","title":"XII-C"},
    {"link":"/daftarmurid/xiid.sql","title":"XII-D"},
    {"link":"/daftarmurid/xiie.sql","title":"XII-E"},
    {"link":"/daftarmurid/xiif.sql","title":"XII-F"},
    {"link":"/daftarmurid/xiig.sql","title":"XII-G"}]}') as menu_item,
    JSON('{"title":"Data Absensi","submenu":
    [{"link":"/datadownloads/data2024.sql","title":"2024"},
    {"link":"/datadownloads/data2025.sql","title":"2025"}]}') as menu_item;
SELECT 'button' AS component, 'center' AS justify;
SELECT 'Daftar Tidak Hadir' AS title, 'daftartidakhadir/xiig.sql' as link;
SELECT 'list' AS component, 'Daftar Kehadiran  Kelas XII-G' AS title;
SELECT nama AS title, kehadiran AS description FROM presence.presencelist WHERE kelas = 'XII-G'; 