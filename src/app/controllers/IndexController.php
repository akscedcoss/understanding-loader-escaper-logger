<?php

use Phalcon\Mvc\Controller;


use \App\Components\myescaper;

class IndexController extends Controller
{
    public function indexAction()
    {
      
        
        // return '<h1>Hello World!</h1>';
    }
    public function logoutAction()
    {
        $this->session->remove('userDetail');
        $this->cookies->get('login-action')->delete();
        return $this->response->redirect('/');
    }

    public function testAction()
    {
    
      $helper= new myescaper();
      echo $helper->sanitize('dfbdf');
    }


}