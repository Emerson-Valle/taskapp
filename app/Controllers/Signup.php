<?php 

namespace App\Controllers;

class Signup extends BaseController
{
    public function new()
    {
		return view("Signup/new");
    }
    
    public function create()
    {
        $user = new \App\Entities\User($this->request->getPost());

        $model = new \App\Models\UserModel;
    
        $user->startActivation();

        log_message('info', 'Token de activación: ' . $user->token);
        
        if ($model->insert($user)) {
            
            log_message('info', 'valleemeron121@gmail.com: ' . $user->email);
            log_message('info', 'Usuario insertado correctamente, enviando correo de activación.'); 

            $this->sendActivationEmail($user);
        
            return redirect()->to("/signup/success");
             log_message('error', 'Error al insertar usuario: ' . json_encode($model->errors()));
        } else {
            
            return redirect()->back()
                             ->with('errors', $model->errors())
                             ->with('warning', 'Invalid data')
                             ->withInput();
        }
    }
    
    public function success()
    {
		return view('Signup/success');
    }
    
    public function activate($token)
    {
        $model = new \App\Models\UserModel;
        
        $model->activateByToken($token);
        
		return view('Signup/activated');
    }
    
    private function sendActivationEmail($user)
    {
        $email = service('email');

        $email->setTo($user->email);

        $email->setSubject('Account activation');

        $message = view('Signup/activation_email', [
            'token' => $user->token
        ]);
        log_message('info', 'Mensaje de correo de activación: ' . $message);
        $email->setMessage($message);

        if (!$email->send()) {
            log_message('error', 'Error al enviar correo de activación: ' . print_r($email->printDebugger(), true));
        } else {
            log_message('info', 'Correo de activación enviado a: ' . $user->email);
        }
    }
}    