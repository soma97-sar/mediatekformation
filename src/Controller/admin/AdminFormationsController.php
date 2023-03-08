<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminFormationsController
 *
 * @author soumia
 */
class AdminFormationsController extends AbstractController{
     /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    function __Construct(FormationRepository $formationRepository) {
        $this->formationRepository = $formationRepository;
        
    }
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        return $this->render("admin/admin.formations.html.twig", [
            'formations' => $formations
            
        ]);
    }
    /**
     * @Route("/admin/delete/{id}", name="admin.formation.delete")
     * @param Formation $formation
     * @return Response
     */
    public function delete(Formation $formation):Response{
        $this->formationRepository->remove($formation, true);
        return $this->redirectToRoute('admin.formations');
    }
    /**
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @return Response
     */
    public function edit(Formation $formation, Request $request):Response{
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted()&& $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
               
        }
        return $this->render("admin/admin.formation.edit.html.twig",[
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
             
    }
    /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request):Response{
        $formation= new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted()&& $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
               
        }
        return $this->render("admin/admin.formation.ajout.html.twig",[
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
             
    }
    
    
}
