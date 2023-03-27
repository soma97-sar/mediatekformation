<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationControllerTest
 *
 * @author soumia
 */
class FormationsControllerTest  extends WebTestCase {
    public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/formations');
       // $this->assertResponseIsSuccessful(Response::HTTP_OK);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode()); 
    }  
    public function testLinkFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        // clic sur le lien (le nom d'une formation)
        $client->clickLink('Eclipse n°8 : Déploiement');
        // récupération du résultat du clic
        $response = $client->getResponse();
        // contrôle si le client existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupération de la route et contrôle qu'elle est correcte
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/formations/formation/1', $uri);
    }
    
    public function testFiltreFormation(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        // simulation de la soumission du formaulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse n°8 : Déploiement'
        ]);
        // vérifie le nombre de lignes obtenues
        $this->assertCount(1, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }
    
}
