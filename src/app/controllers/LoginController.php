<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
class LoginController extends Controller
{
    public function indexAction()
    {
        
        if( $this->session->has('userDetail'))
        {
            return $this->response->redirect('/');
        }
        // Check if cookie avialble
        if ($this->cookies->has("login-action")) {
            $loginCookie = $this->cookies->get("login-action"); 
        // Get the cookie's value 
         $value=$loginCookie->getValue(); 
         $value=json_decode($value);
        // assign email and name to sessions
        $this->session->set('userDetail', $value);
        return $this->response->redirect('/');
        }
    }

    public function loginAction()
    {
        $user = new Users();
        $user->assign(
            $this->request->getPost(),
            [
                'email',
                'password'
            ]
        );

        // $check=Users::find("email='$user->email'");
        $check=Users::find([
            'conditions' => 'password = :password: and email = :email:' ,
            'bind'       => [
                'password' =>$user->password ,
                'email' => $user->email,
            ]
        ]);
        //if wrong credentiols
        if($check->count()==0) {
            $response = new Response();
            $response->setStatusCode(403, 'Not Found');
            $response->setContent("Sorry, Authecation Failed");
            $response->send();
            $adapter = new Stream('../app/logs/signin.log');
            $logger  = new Logger(
                'messages',
                [
                    'main' => $adapter,
                ]
            );

            $logger->error("Sorry, Login Failed");
            die();
        }
        // if user email and password is correct we need to create session
        $this->session->set('userDetail', (object)$check[0]);
        // if remebr me button set cookie 
        if($this->request->getPost()['chk'])
        {
            $this->cookies->set(   
                "login-action",   
                json_encode(["email"=>$check[0]->email ,"name"=>$check[0]->name]),   
                time() + 15 * 86400   
             );  
            
        }
        $adapter = new Stream('../app/logs/signin.log');
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        $logger->info("Logged in");
        // redirect to the dashboard 
        return $this->response->redirect('/');

    
           
    }
}