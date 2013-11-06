Quiz-em
================================

Quiz-em is an quiz application licensed under the [GPL license] [4]. It is designed for use in organizations that push out education to users on a regular basis or have annual competencies, ie hospitals.  Supports LDAP (Active Directory) or MySQL authentication built in, also can support other authenication methods (read comments in include/auth.php).

I wrote this in procedural style (learned php back in 2002 prior to php5) but am in the process of converting to object orientated.

Live Demo
-------------------
[Click here for live demo.] [8]



User Access
-------------------

Allows for three levels of access
* User: Acess to log in and take quizes.
* Creator: Above plus, access to create quizes, modify own quizes, and check for completion of owned quizes.
* Admin: Full access, add/modify/search users, quizes, staff types; check for completion of any quizes; change quiz ownership.

SQL Setup
-------------------

Using phpmyadmin or whatever you want run the quiz-em_mysql_setup.sql to create the database and tables.


functions.php parameters
-------------------------

In the functions.php change the MySQL info in the `db_connect()` function to yours

	$db='quiz';
	$mysql_host = "db server";
	$mysql_user = "sql user";
	$mysql_password = "password";


See [MySQL-PHP-Functions.md] [6] for a little more detail on how the functions handle posted data arrays to generate SQL statements dynamically.

More documentation to come in my spare time.
-------------------


File Uploader
-------------------------

Quiz'em uses the [JQuery File Upload Plugin] [5]. The upload handler has been modified to save the files in a MySQL database as a 'blob' and are retrived using view.php in the img folder. Any file type can be uploaded. Minor changes where made to the JQuery front end to improve display and remove the thumbnail preview of images. The main.js was modified to pull the url to post data to from the action field of the form. See below.

	$('#fileupload').fileupload({
	        // Uncomment the following to send cross-domain cookies:
	        //xhrFields: {withCredentials: true},
	        url: $("#fileupload").attr('action')
	    });


Written using [php] [1], [MySQL] [2], [JQuery] [3] and [Bootstrap] [7]

  [1]: http://us.php.net/        "php"
  [2]: http://www.mysql.com/  "MySQL"
  [3]: http://jquery.com/    "JQuery"
  [4]: http://opensource.org/licenses/GPL-3.0
  [5]: https://github.com/blueimp/jQuery-File-Upload 
  [6]: https://github.com/andrewlong/Quiz-em/blob/master/MySQL-PHP-Functions.md
  [7]: http://getbootstrap.com/
  [8]: http://quiz.andrewlong.pw/
