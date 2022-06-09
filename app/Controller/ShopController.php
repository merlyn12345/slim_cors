<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class ShopController extends Controller
{
    public function default(Request $request, Response $response):Response
    {
        $userId = $this->ci->get('session')->get('userId');

        $sql = "SELECT nahrungID, Name, `Energie (cal)`, amount, stime FROM user_nahrung LEFT JOIN nahrung ON nahrung.nahrungId = user_nahrung.u_n_nahrungId AND user_nahrung.u_n_userId = '".$userId."'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $nahrungsmittel = $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $nahrungsmittel =json_encode($error);

        }
        return $this->render($response, 'shop-default.html', ["nahrungsmittel" => $nahrungsmittel]);
    }

    public function details(Request $request, Response $response, $args=[]):Response
    {
        echo 'id:'.$args['id'];
        $error = false;
        $sql = "SELECT nahrungID, Name FROM nahrung WHERE nahrungID = '".$args['id']."'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $nahrungsmittel = $stmt->fetchAll();


        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $nahrungsmittel =json_encode($error);

        }
        // deshalb
        if($error){
            throw new HttpNotFoundException($request, $response);
        }

        return $this->render($response, 'details.html', ['nahrungsmittel' => $nahrungsmittel]); //array index of false ist das erste element
    }

    public function kategorien(Request $request, Response $response):Response
    {
        $kategorien = [];
        $sql = "SELECT DISTINCT kategorie FROM nahrung ORDER BY kategorie ASC";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $kategorien = $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $kategorien =json_encode($error);

        }
        return $this->render($response, 'submit.html', ["kategorien" => $kategorien]);
    }


    public function items(Request $request, Response $response, $args=[]):Response
    {
        $items = [];
        $sql = "SELECT nahrungId, Name  FROM nahrung WHERE kategorie = '".$args['kategorie']."'";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $items= $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $items =json_encode($error);

        }
        return $this->render($response, 'items.html', ["items" => $items]);
    }

    public function submit(Request $request, Response $response):Response
    {
        $recieved = $request->getParsedBody();  // da könnnen noch ein paar checks hin
        //$response->write(json_encode($recieved).'array:'.count($recieved));
        //return $response->withHeader('Content-Type', 'text/html');
        $userId = $this->ci->get('session')->get('userId');
        if(count($recieved) != 3){
            $response->write('Error parsing data'.' userid '.$userId);
            return $response->withHeader('Content-Type', 'text/html');
        }
        $userId = $this->ci->get('session')->get('userId');
        $sql = "INSERT INTO user_nahrung (u_n_userId, u_n_nahrungId, amount, ltime, stime) VALUES ('".$userId."', '".$recieved['item']."', '".$recieved['quantity']."', NOW(), NOW())";

        try {
            $stmt = $this->ci->get('db')->query($sql);
            $items= $stmt->fetchAll();

        } catch(PDOException $e) {
            $error = array(
                'message' => $e->getMessage()
            );

            $items =json_encode($error);

        }
       return $this->render($response, 'default.html', ["items" => json_encode($items)]);  // Fehler- oder success mitgeben
    }
}