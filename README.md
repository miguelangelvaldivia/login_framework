login_framework
===============

Basic framework for a codeigniter site that required users to login via email address and password.

Changes need to be made to the system to make it usable.

- .htaccess needs to have the RewriteBase value changed to the new base of whatever you call the sytem.

- application/config/database.php needs to have proper database credentials set up.

- Proper database tables need to be set up:
	- temp_user
		- id		(int)	Primary Key, Auto Increment, Not Null
		- email		(varchar[255])
		- password	(varchar[255])
		- key		(varchar[255])
		
	- user
		- id		(int)	Primary Key, Auto Increment, Not Null
		- email		(varchar[255])
		- password	(varchar[255])
