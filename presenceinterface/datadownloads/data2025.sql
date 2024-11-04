SELECT 
    'shell' AS component, 
    'Data 2025' AS title,
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
SELECT 'list' AS component, 'Data Absensi 2025' as title;
SELECT 'Januari' AS title, '2025/jan.sql' as link;
SELECT 'Februari' AS title, '2025/feb.sql' as link;
SELECT 'Maret' AS title, '2025/mar.sql' as link;
SELECT 'April' AS title, '2025/apr.sql' as link;
SELECT 'Mei' AS title, '2025/may.sql' as link;
SELECT 'Juni' AS title, '2025/jun.sql' as link;
SELECT 'Juli' AS title, '2025/jul.sql' as link;
SELECT 'Agustus' AS title, '2025/aug.sql' as link;
SELECT 'September' AS title, '2025/sep.sql' as link;
SELECT 'Oktober' AS title, '2025/oct.sql' as link;
SELECT 'November' AS title, '2025/nov.sql' as link;
SELECT 'December' AS title, '2025/dec.sql' as link;