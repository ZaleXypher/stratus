SELECT 
    'shell' AS component, 
    'Stratus' AS title,
    '1' AS sidebar,
    'dark' AS theme,
    JSON('{"title":"Daftar Kelas","submenu":[{"link":"/daftarmurid/xiid.sql","title":"XII-D"}]}') as menu_item;
SELECT 
    'hero'                 as component,
    'Stratus'              as title,
    'Attendance Made Easy.' as description_md,
    '/logo.png' as image;
SELECT 
    'list' AS component, 
    'Daftar Kelas' AS title;

    SELECT 'XIID' AS title, 
    '/daftarmurid/xiid.sql' as link;

SELECT 
    'button' AS component, 
    'center' as justify;

