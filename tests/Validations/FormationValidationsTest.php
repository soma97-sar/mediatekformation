<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author soumia
 */
class FormationValidationsTest extends KernelTestCase {
    /**
     * 
     * @return Formation
     */
    public function getFormation(): Formation {
        return (new Formation())
                ->setTitle("POO TP Java n°2 : MVC");
               
        
    }
    /**
     * 
     * @param Formation $formation
     * @param int $nbErreursAttendues
     * @param string $message
     */
     public function assertErrors(Formation $formation, int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error, $message);
    }
    public function testValidPublishedAt() {
        $aujourdhui= new \DateTime();
        $this->assertErrors($this->getFormation()->setPublishedAt($aujourdhui),0,"aujourd'hui devrait reussir");
        $plutot=(new \DateTime())->sub(new \DateInterval("P5D"));
        $this->assertErrors($this->getFormation()->setPublishedAt($plutot),0,"plus tôt devrait reussir");
        
    }
    public function testNoValidPublishedAt(){ 
        $demain = (new \DateTime())->add(new DateInterval("P1D"));
        $this->assertErrors($this->getFormation()->setPublishedAt($demain), 1, "demain devrait échouer");
        $plustard = (new \DateTime())->add(new \DateInterval("P5D"));
        $this->assertErrors($this->getFormation()->setPublishedAt($plustard), 1, "plus tard devrait échouer");
    }
          
}
