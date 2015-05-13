<?php
class page
{
	private $header;
	private $footer;
	private $username;
	private $association_name;
	
	public function __construct($user_association)
	{
		if (isset($user_association))
		{
			$this->username = $user_association['username'];
			$this->association_name = $user_association['association_name'];
		}
	}
	
	private function setTemplate($name)
	{
		switch ($name)
		{
			case 'default':
				$file = 'templates/default.html';
			break;

			case 'no_user':
				$file = 'templates/no_user.html';
			break;

			default:
				$file = 'templates/default.html';
		}
		

		$template = file_get_contents($file);
		$tags = array('[$base]','[$username]','[$association_name]');
		$replaces = array('http://'.$_SERVER['SERVER_NAME'].'/',ucfirst($this->username), $this->association_name);
		$template = str_replace($tags,$replaces,$template);

		$template = explode('[$split]',$template);
		$this->header = $template[0];
		$this->footer = $template[1];
	}
	
	public function starting($title,$access = "",$template = 'default')
	{
		$this->setTemplate($template);
		
		$header = str_replace('[$title]',$title,$this->header);

		switch($access)
		{
			case "public":
			$head = $header;
			break;
			
			case "user":
			global $token;
			if (empty($token) || !isset($token))
			{
				header("Location: login.php");
				exit;
			}
			$head = $header;
			break;
			
			default:
			$head = $header;
		}
		$this->header=$head;
		return $this->header;		
	}
	
	public function ending()
	{
		return $this->footer;
	}
}
?>