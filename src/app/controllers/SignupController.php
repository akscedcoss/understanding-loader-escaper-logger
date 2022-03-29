<?php

use Phalcon\Mvc\Controller;

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
        $user = new Users();
        // print_r( $this->request->getPost()) ;
        // die();
        $user->assign(
            $this->request->getPost(),
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
