# CSC350-Term-Project
Social Media Project

Social Meet is a term project for CSC 350, it is a simple messaging/social media website used to help people communicate with one another.


----- IT IS REQUIRED TO IMPORT THE DATABASE 'social_meet.sql' OR ELSE NONE OF THE CODE WILL FUNCTION. -----

----- XAMPP and MySql IS ALSO REQUIRED -----


To import the `social_meet.sql` file on someone else's XAMPP, you can follow these steps:

1. Copy the `social_meet.sql` file to the other person's computer.
2. Start the XAMPP Control Panel on their computer and make sure that the MySQL server is running.
3. Open a command prompt or terminal window and navigate to the XAMPP installation directory. For example, on Windows, you can type `cd C:\xampp\mysql\bin`.
4. Run the following command to import the SQL file:

```
mysql -u [username] -p social_meet < path/to/social_meet.sql
```

Replace `[username]` with your MySQL username (root by default) and `path/to/social_meet.sql` with the path to the `social_meet.sql` file on your computer.

5. Enter your MySQL password when prompted.

After running this command, the `social_meet` database and its tables should be imported into your XAMPP MySQL server and the code should function as intended.
