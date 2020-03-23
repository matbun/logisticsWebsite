<?php 
// user info class
class UserInfo{
	/**
	 * @param user_id is the User Id in the database
	 * @param username is the username used to authenticate
	 * @param user_type is the type of the user: admin, driver...
	 * @param developer is true if the user has developer rights (to make tests)
	 * @return nothing
	 * @author 
	 **/
	function __construct($user_id, $username, $user_type, $developer){
		$this->user_id = $user_id; //db ID
		$this->username = $username;
		$this->user_type = $user_type;
		$this->developer = $developer;
		// here is possible to add more info... (es mail, tel...)
	}
}
 ?>