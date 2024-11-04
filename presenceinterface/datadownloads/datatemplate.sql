SELECT 
    'shell' AS component, 
    'Data PLACEHOLDER' AS title,
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
SELECT 'list' AS component, 'Data Absensi PLACEHOLDER' as title;
SELECT 'Januari' AS title, 'PLACEHOLDER/jan.sql' as link;
SELECT 'Februari' AS title, 'PLACEHOLDER/feb.sql' as link;
SELECT 'Maret' AS title, 'PLACEHOLDER/mar.sql' as link;
SELECT 'April' AS title, 'PLACEHOLDER/apr.sql' as link;
SELECT 'Mei' AS title, 'PLACEHOLDER/may.sql' as link;
SELECT 'Juni' AS title, 'PLACEHOLDER/jun.sql' as link;
SELECT 'Juli' AS title, 'PLACEHOLDER/jul.sql' as link;
SELECT 'Agustus' AS title, 'PLACEHOLDER/aug.sql' as link;
SELECT 'September' AS title, 'PLACEHOLDER/sep.sql' as link;
SELECT 'Oktober' AS title, 'PLACEHOLDER/oct.sql' as link;
SELECT 'November' AS title, 'PLACEHOLDER/nov.sql' as link;
SELECT 'December' AS title, 'PLACEHOLDER/dec.sql' as link;