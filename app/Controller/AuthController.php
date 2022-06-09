<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{
    public function login(Request $request, Response $response)
    {

        if($request->isPost()){
            $userarray = $this->authenticateUser($request);
            if(count($userarray) == 1){
                $this->ci->get('session')->set('user', $request->getParam('username'));
                $this->ci->get('session')->set('userId', $userarray[0]['userId']);
                return $response->withRedirect('/secure');
            }

        }
        return $this->render($response, 'login.html');
    }
    public function logout(Request  $request, Response $response)
    {
        $this->ci->get('session')->delete('user');
        $this->ci->get('session')->delete('userId');
        return $response->withRedirect('/');
    }

    private function authenticateUser(Request $request){
        $userarray=[];
        $username =  $request->getParam('username');
        $sql="SELECT userId, username FROM user WHERE username='$username'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $userarray = $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );


        }
        return $userarray;
    }
}