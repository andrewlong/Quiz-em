SQL insert/update framework
The staff_type page is a quick exampe way to handle inserting rows in a MySQL database. Currently there is no mechanism for deleting data but that could be added, right now my design is planned around the fact I don't want users to delete info rather they are inactivate it.

The page processes through an html input array $input and dynamnically generates UPDATE and INSERT statements using the function `process_post_data`. The page also includes a search mechanism and editing interface that is access from the search results by clicking on the row.

This is all written with the MySQLi php interface
			
Info on expected SQL table schemas
Each table needs to have a autoincrement identiy column this colum must be name "id", other than that any other column types should work. The posted values are escaped for security but there is no other checking of the data done. 
Example table schema

	CREATE TABLE IF NOT EXISTS `users` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_name` varchar(100) NOT NULL,
	  `password` varchar(100) DEFAULT NULL,
	  `active` tinytext NOT NULL,
	  PRIMARY KEY (`id`)
	);
					
Setting up the form
In the test template you can change the input[] array is necessary and you can run multiple getdata($table); different information. 
The input[key] array key needs to be the the column name 
$table needs to be set to the table you are using to store the information.

	$table = 'users';
	....
	$data= get_data($table);
	
	text_input('input[user_name]',$data['user_name'],'text',4);
	text_input('input[password]',$data['password'],'text',4);
	$yes_no = array(
					array('Y','Yes'),
					array('N','No')
					);
	select_input('input[active]',$yes_no,$data[active]);