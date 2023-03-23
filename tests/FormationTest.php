<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author soumia
 */
class FormationTest extends TestCase{
    public function testgetPublishedAtString() {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2021-01-04"));
        $this->assertEquals("04/01/2021", $formation->getPublishedAtString());
        
    }
}
