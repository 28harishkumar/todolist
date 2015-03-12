<?php
require_once('includes/cmsApplication.php');

//this file takes care of authentication of the users visiting the website.

class AccountApplication extends CmsApplication{
	// default function that will run if no operation is choosen
	function display()
	{
		//check if any session exists otherwise login
		$this->checklogin('index.php?app=account&task=logintask');
	}

	//verify if any session exists
	private function checklogin($url = null)
	{
		if(isset($_SESSION['user_session']))
		{
			$this->redirect();
		}
		if($url !== null)
		{
			$this->redirect($url);
		}
	}

	// sends user information
	private function getUserInfo($email)
	{
		$db=$this->getDbo();
		$query = $db->loadSingleResult("SELECT * FROM users WHERE email = ?",array($email));
		return $query;
	}

	private function login($email,$password)
	{
		$db=$this->getDbo();
		$email = $db->quote($email);
		$password = hash('sha256', $password);
		$query = $db->loadSingleResult("SELECT * FROM users WHERE email=? AND password=? AND is_active = 1",array($email,$password));

		if(isset($query['id']))
		{
			session_start();
			$_SESSION['user_session'] = $query;
			if(isset($_SESSION['user_session']))
			{
				$this->redirect();
			}
			else{
				$url = 'index.php?app=account&l_error=Email+and+password+mismatch&task=logintask';			
			}
		}
		else
		{
			$url = 'index.php?app=account&l_error=Email+and+password+mismatch&task=logintask';
		}
		$this->redirect($url);
	}

	private function logout()
	{
		session_start();
		if(isset($_SESSION['user_session']) && $_SESSION['user_session'])
		{
			session_destroy();
		}
		$this->redirect('index.php?app=account&task=logintask');
	}

	private function register($email,$password)
	{
		$db=$this->getDbo();
		$eml = $db->quote($email);
		if($this->getUserInfo($eml))
		{
			$this->infoError('alreadyexist');
		}
		$password = hash('sha256', $password);
		$code = hash('sha256', 'register'.$email.''.time());
		$query = $db->prepare("INSERT INTO users (email,password,confirm_code) VALUES(?,?,?)",array($eml,$password,$code));
		$count = $query->rowCount();
		if($count)
		{
			$this->sendRegisterEmail($email,$code);
		}
		return $count;
	}

	private function emailMessage($title,$link,$message)
	{
		$body="<html>
		<body>
			<h1>".$title."</h1>
			<p>Click on the link <a href=".$link.">".$message."></a></p>
			<p><a href='index.php'>Go Back to Home page</a></p>
			</br></br></br></br></br>
			<small>It is auto generated message. Don't reply for this message. Only click on the links.</small>
		</body>
		</html>";
		return $body;
	}

	//send email config your init.php for working this function
	private function sendRegisterEmail($email,$code)
	{
		$subject = "My to do email register";
		//put domain name instead of localhost
		$link = 'http://localhost/todo/?app=account&task=verifyEmail&i='.$code.'&e='.$email;
		$title = 'Confirm email for registeration';
		$message = 'Confirm Email';
		$txt = $this->emailMessage($title,$link,$message);
		$headers = "From: webmaster@example.com" . "\r\n" .
		"CC: somebodyelse@example.com";

		mail($email,$subject,$txt,$headers);
	}

	// match the confirmation code to verify email
	function verifyEmail()
	{
		$this->checklogin();
		if(!isset($_GET['i']) or !isset($_GET['e']))
		{
			$this->redirect();
		}

		$code = $_GET['i'];
		$email = $_GET['e'];
		$db=$this->getDbo();
		$email = $db->quote($email);
		$data = $this->getUserInfo($email);
		$query = null;
		if($data['confirm_code']==$code && $code != '0')
		{
			$sql = "UPDATE users as user SET user.is_active='1', user.confirm_code='0' WHERE user.email= ?";
			$query = $db->prepare($sql,array($email));
		}
		else
		{
			$this->redirect();
		}

		if($query->rowCount())
		{
			session_start();
			$_SESSION['user_session'] = $data;
		}
		$this->redirect();
	}

	// take login data from user
	function loginuser()
	{
		$this->checklogin();
		$email = $_POST['login-email'];
		$password = $_POST['password'];
		$this->login($email,$password);
	}

	function logoutuser()
	{
		$this->logout();
	}

	// send error if any exists during authentication
	private function infoError($error_code = null){
		switch ($error_code) {
			case 'incomplete':
				$error = 'Password+mismatch';
				break;
			case 'passmismatch':
				$error = 'Fill+all+of+the+fields';
				break;
			case 'alreadyexist':
				$error = 'Email+alrady+exists';
				break;
			case 'loginfail':
				$error = 'Email+and+password+mismatch';
				break;
			
			default:
				$error = 'Some+error+occur';
				break;
		}
		$url = 'index.php?app=account&error='.$error.'&task=logintask';
		$this->redirect($url);
	}

	// take register data from user
	function registeruser()
	{
		$this->checklogin();
		$email = $_POST['email'];
		$pass1 = $_POST['password1'];
		$pass2 = $_POST['password2'];
		if(!isset($email) or !isset($pass1) or !isset($pass2))
		{
			$this->infoError('incomplete');
		}
		
		if($pass1 !== $pass2)
		{
			$this->infoError('passmismatch');
		}

		if($this->register($email,$pass1))
		{
			$message = 'You have register succesfully. Now open your email id and click on the confirmation link';
			$heading = 'Success';		
			require('applications/account/message.php');
		}
		else
		{
			$this->infoError();
		}
	}

	// send account form
	function logintask()
	{
		$this->checklogin();
		require('applications/account/auth.php');
	}

	// recieve forget password form
	function forgetpassword()
	{
		$this->checklogin();
		if(!isset($_POST['forget-email']))
		{
			$redirect();
		}

		$email = $_POST['forget-email'];
		$code = hash('ripemd160','forget'.$email.''.time());
		$db=$this->getDbo();
		$eml = $db->quote($email);
		$sql = "UPDATE users SET confirm_code= '$code' WHERE email= ?";
		$query = $db->prepare($sql,array($eml));
		if($query->rowCount())
		{
			$this->sendResetEmail($code,$email);
			$message = 'Your reset password information has been sent to your email address.';
			$heading = 'Success';		
		}
		else
		{
			$message = 'You have provide invalid email address';
			$heading = 'Error';
		}
		require('applications/account/message.php');
	}

	private function sendResetEmail($code,$email)
	{
		$subject = "My to do password reset";
		//put domain name instead of localhost
		$link = 'http://localhost/todo/?app=account&task=resetPassword&i='.$code.'&e='.$email;
		$title = 'Reset Password';
		$message = "confirm reset Password";
		$txt = $this->emailMessage($title,$link,$message);
		$headers = "From: webmaster@example.com" . "\r\n" .
		"CC: somebodyelse@example.com";

		mail($email,$subject,$txt,$headers);
	}

	function resetPassword()
	{
		$this->checklogin();
		if(!isset($_GET['i']) or !isset($_GET['e']))
		{
			$this->redirect();
		}

		$code = $_GET['i'];
		$email = $_GET['e'];
		$db=$this->getDbo();
		$email = $db->quote($email);
		$data = $this->getUserInfo($email);
		$query = null;
		if($data['confirm_code']==$code && $code != '0')
		{
			$code = hash('sha256','resetPassword'.time().''.$email);
			$sql = "UPDATE users as user SET user.confirm_code='$code' WHERE user.email= ?";
			$query = $db->prepare($sql,array($email));
		}
		else
		{
			$this->redirect();
		}

		if($query!=null and $query->rowCount())
		{
			require('applications/account/resetpassword.php');
		}
		else
		{
			$this->redirect();
		}
	}

	function resettask()
	{
		$this->checklogin();
		if(!isset($_POST['i']) or !isset($_POST['e']) or !isset($_POST['pass1']) or !isset($_POST['pass2']))
		{
			require('applications/account/message.php');
			exit();
		}

		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		$email = $_POST['e'];
		$code = $_POST['i'];
			
		if($pass2 !== $pass1)
		{
			$error = 'password mismatch';
			require('applications/account/resetpassword.php');
			exit();
		}

		$db=$this->getDbo();
		$eml = $db->quote($email);
		$data = $this->getUserInfo($eml);
		$query = null;

		if($data['confirm_code']==$code && $code != '0')
		{
			$sql = "UPDATE users as user SET user.confirm_code='0' WHERE user.email= ?";
			$query = $db->prepare($sql,array($eml));
		}
		else
		{
			$this->redirect();
		}

		if($query and $query->rowCount())
		{
			$pass1 = hash('sha256', $pass1);
			$db=$this->getDbo();
			$eml = $db->quote($email);
			$sql = "UPDATE users SET confirm_code= '0', password = ? WHERE email= ?";
			$query = $db->prepare($sql,array($pass1,$eml));
			if($query->rowCount())
			{
				$message = 'Your reset password has been reseted.';
				$heading = 'Success';		
			}
			else
			{
				$message = 'You have provide invalid data';
				$heading = 'Error';
			}
		}
		else
		{
			$message = 'You have provide invalid data';
			$heading = 'Error';
		}
		require('applications/account/message.php');
	}
}
?>