<h1>Stratus</h1>
Strada Automated Attendance System is a student attendance system, meant to be used in conjunction with an RFID attendance card to automate reporting of student's attendance.<br>
Made for P5 assignment<br>
Uses PostgreSQL to store attendance data and student list
<h1>Features</h1>
<h2>Home Page</h2>

![homepage](/readmeresources/homepage.png)
<h2>Attendance List Sorted by Class w/Attendance Status</h2>

![attendance list](/readmeresources/presencelist.png)
<h2>List of Absent Students</h2>

![absence list](/readmeresources/absencelist.png)
<h2>Data Downloads Sorted by Year and Month</h2>

![data downloads](/readmeresources/datadownload.png)
<h2>Interface to Change Attendance Status</h2>

![status](/readmeresources/changestatus.png)

<h1>How to Run</h1>
<li>Clone the repository</li>
<li>Start a PostgreSQL server, i.e with Docker</li>
<li>Modify Config</li>
Replace DB credentials in 

  ```
  presenceapi/config/config.php
  ``` 
  and 
  ```
  presenceinterface/sqlpage/sqlpage.json
  ```

Replace references to localhost in 

  ```
  presenceinterface/daftarmurid/_____.php
  ```


  ```
  presenceinterface/daftarmurid/daftartidakhadir/_____.php
  ```

<li>Run the following command in the Postgres DB</li>

```
CREATE TABLE studentlist(kelas TEXT, absen INT, nama TEXT, id VARCHAR)
```

<li>Run a PHP server in presencelist on port 8090</li>
For example if you are using PHP Development Server

```
cd presencelist
```
```
php -S localhost:8090
```

<li>Run SQLPage</li>

```
cd presenceinterface
```
```
./sqlpage.bin
```
<h1>Credits</h1>
https://github.com/sqlpage/SQLPage for UI framework

