<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
        return view("Home/index");
	}

	public function testEmail()
	{
        $email = service('email');
        
        $email->setFrom('postmaster@sandboxfe5ffc64f63640a79d542cdc12dff9c1.mailgun.org', 'Nombre del Remitente');
		
        $email->setTo('valleemerson121@gmail.com');
		
        $email->setSubject('A test email');
		
        $email->setMessage('<h1>Hello world</h1>');
		
        if ($email->send()) {
		
            echo "Message sent";
			
		} else {
		
            echo $email->printDebugger();
			
		}
	}
}