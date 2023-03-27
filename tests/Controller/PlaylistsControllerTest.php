<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistControllerTest
 *
 * @author soumia
 */
class PlaylistsControllerTest extends WebTestCase{
     public function testAccesPage(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
       // $this->assertResponseIsSuccessful(Response::HTTP_OK);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode()); 
    } 
     public function testContenuPage(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $this->assertSelectorTextContains('p', 'Les playlists des formations de mediatekformation');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(3, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    
    public function testLinkPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        // clic sur le lien (le nom d'une playlist)
        $client->clickLink('Bases de la programmation (C#)');
        // récupération du résultat du clic
        $response = $client->getResponse();
        // contrôle si le client existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        // récupération de la route et contrôle qu'elle est correcte
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/playlists/playlist/13', $uri);
    }
    
    public function testFiltrePlaylist(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        // simulation de la soumission du formaulaire
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Bases de la programmation (C#)'
        ]);
        // vérifie le nombre de lignes obtenues
        $this->assertCount(0, $crawler->filter('h5'));
        // vérifie si la playlist correspond à la recherche
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
   
}
