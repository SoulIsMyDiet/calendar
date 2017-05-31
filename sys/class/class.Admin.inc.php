<?php

//declare (strict_types=1); //uncomment at php7

class Admin  extends DB_Connect{

private $_saltLength = 7;

public function __construct($db=NULL, $saltLength=NULL){

	parent::__construct($db);
	
	if (is_int($saltLength))
	{
		$this->_saltLength = $saltLength;
	}
}
public function processLoginForm(){
	if( $_POST['action']!='user_login' )
	{
		return "Nieprawidłowa wartość parametru action w metodzie processLoginForm";
	}
	$uname = htmlentities($_POST['uname'], ENT_QUOTES);
	$pword = htmlentities($_POST['pword'], ENT_QUOTES);
	
	$sql = "SELECT user_id, user_name, user_email, user_pass FROM users WHERE user_name =:uname LIMIT 1;";
	try
	{
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":uname", $uname, PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($user[0]);
		$stmt->closeCursor();
	}
	catch(Exception $e)
	{
		die($e->getMessage());
	}
	if(!isset($user))
	{
		return "Nieprawidłowa nazwa użytkownika lub hasło.";
	}
	$hash = $this->_getSaltedHash($pword, $user[0]['user_pass']);
echo $hash."<br/>";
echo "lol";
echo "lol";
echo $user[0]['user_pass'];	
echo "lol";
	if ($user[0]['user_pass'] == $hash )
	{
		$_SESSION['user'] = ['id' => $user[0]['user_id'], 'name' => $user[0]['user_name'], 'email' => $user[0]['user_email']];
		return TRUE;
	}
	else
	{
		return "Nieprawidłwa nazwa użytkownika lub hasło";
	}
}
public function processLogout(){
	if ($_POST['action']!='user_logout')
	{
		return "Nieprawidłowa wartość parametru action w metodzie processLogout.";
	}
	session_destroy();
	return TRUE;
}
private function _getSaltedHash($string, $salt=NULL){

	if($salt ==NULL)
	{
		$salt = substr(md5((string)time()), 0, $this->_saltLength);
	}
	else
	{
		$salt = substr($salt, 0, $this->_saltLength);
	}
	return $salt.sha1($salt.$string);
}
public function testSaltedHash($string, $salt=NULL){
	return $this->_getSaltedHash($string, $salt);
}



}
