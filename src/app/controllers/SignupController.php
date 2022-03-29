<?php

use Phalcon\Mvc\Controller;
use \App\Components\myescaper;
class SignupController extends Controller
{

    public function IndexAction()
    { 
        if( $this->session->has('userDetail'))
        {
            return $this->response->redirect('/');
        }
    }

    public function registerAction()
    {
       
        // print_r( $this->request->getPost()) ;
        // die();
        $helper= new myescaper();
        // echo $helper->sanitize('dfbdf');

        $data=[
            'name'=>$helper->sanitize($this->request->getPost()['name']),
            'email'=>$helper->sanitize($this->request->getPost()['email']),
            'password'=>$helper->sanitize($this->request->getPost()['password']),
        ];
        $user= new Users();
        $user->assign(
            $data,
            [
                'name',
                'email',
                'password'
            ]
        );

        $success = $user->save();

        $this->view->success = $success;

        if ($success) {
            $this->view->message = "Register succesfully";
        } else {
            $this->view->message = "Not Register succesfully due to following reason: <br>" . implode("<br>", $user->getMessages());
        }
    }
}
