<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;

 class ApiController extends Controller
 {


     public function search(Request $request, Response $response): Response
     {
         $albums = json_decode(file_get_contents(__DIR__.'/../../data/albums.json'), true);

         $query = $request->getQueryParam('q');

         if($query == ''){
             return $response->withStatus(400)->withJson(["error" => "Invalid request"]);
         }

         if($query) {
             $albums = array_values(array_filter($albums, function($album)
                 use ($query) {
                     return strpos($album['title'], $query) !== false || strpos($album['artist'] , $query) !== false;

             }));
         }

         return $response->withJson($albums);
     }

     public function default(Request $request, Response $response, $args):Response
     {
         //$userId =  ($this->ci->get('session')->get('userId')) ? ($this->ci->get('session')->get('userId')) : ($args['userId']);
         $token = ($args['token']) ? ($args['token']) : null;

         //$sql = "SELECT nahrungID, Name, `Energie (cal)`, amount, stime FROM user_nahrung LEFT JOIN nahrung ON nahrung.nahrungId = user_nahrung.u_n_nahrungId AND user_nahrung.u_n_userId = '".$userId."'";
         $sql = "SELECT nahrungID, Name, `Energie (cal)`, amount, stime FROM user_nahrung LEFT JOIN nahrung ON nahrung.nahrungId = user_nahrung.u_n_nahrungId AND user_nahrung.u_n_userId = (SELECT userId FROM user WHERE token = '$token')";
         try {
             $stmt = $this->ci->get('db')->query($sql);
             $nahrungsmittel = $stmt->fetchAll();
             $nahrungsmittel = json_encode($nahrungsmittel, JSON_HEX_QUOT);

         } catch(PDOException $e) {
             $error = array(
                 'message' => $e->getMessage()
             );

             $nahrungsmittel =json_encode($error);

         }
         return $response->write($nahrungsmittel)
             ->withHeader("Content-type", 'application/json');
     }

}
