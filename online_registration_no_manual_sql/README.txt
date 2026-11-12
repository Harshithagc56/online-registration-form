ONLINE REGISTRATION FORM - AUTOMATIC DATABASE SETUP

No manual SQL file import is required for local XAMPP use.
The PHP database.php file automatically creates the database and table.

RUN LOCALLY WITH XAMPP
1. Download and install XAMPP for Windows from https://www.apachefriends.org/
2. Open XAMPP Control Panel.
3. Click Start next to Apache.
4. Click Start next to MySQL.
5. Extract this project folder into C:\xampp\htdocs\
6. Open this URL in your browser:
   http://localhost/online_registration_auto_database/

On the first visit, database.php automatically creates:
- Database: student_registration
- Table: registrations

Submit the form. PHP saves the registration using a PDO prepared statement.
You can optionally view saved records at http://localhost/phpmyadmin
(no manual SQL is needed).

CLOUD DEPLOYMENT
For cloud hosting, create a managed MySQL database first and configure:
DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD
as environment variables in your web service. The database itself must exist;
the app will automatically create the registrations table if permitted.
