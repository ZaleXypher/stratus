-- Active: 1734583122890@@0.0.0.0@5432@strada@presence
CREATE TABLE studentlist(  
    kelas TEXT,
    absen INT,
    nama TEXT,
    id VARCHAR
);
INSERT INTO presence.studentlist(kelas, absen, nama)
VALUES
('placekelas', '1', ''),
('placekelas', '2', '');