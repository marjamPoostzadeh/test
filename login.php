<?php
defined( '_VALID_ACCESS' ) or die( 'Restricted Access' );
$title[$section][$module] = 'ورود به بخش مدیریت';
//session_start(); 
if(@$_GET['logout'])
{ 
	unset($_SESSION['admin']);
	redirect("index.php?option=adm_login&flag=8");
}
elseif(isset($_POST['frm_username']))
{
	
	$username   = $_POST['frm_username'];
	$password   = $_POST['frm_password'];
	if ((strspn($password,"'=()-&^*")+strspn($username,"'=()-&^*"))>0) redirect("index.php?option=adm_login&flag=9");
	$filter = ['username' => $username, 'password' => $password ];
	$options = [
		'projection' => ['username' => 1]
	];
	$query = new MongoDB\Driver\Query($filter, $options);
	$user = $manager->executeQuery('BloggerDB.users', $query);
	foreach($user as $r){
	   $r=$user;
	}
	if(!$r)
		redirect("index.php?option=adm_login&flag=6");
	else
	{
		//print_r($user);
		$_SESSION['admin'] = $username;
		if($module != 'login')
				redirect($href);
		else
		redirect("index.php?option=adm_home&flag=7");
	}
	
}
$tplModule = '_login.tpl';
?>