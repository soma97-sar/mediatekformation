<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Description of AdminCategorieController
 *
 * @author BEN BAHA
 */
class AdminCategorieController extends AbstractController{
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    /**
     * 
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository= $categorieRepository;
        
    }
    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index():Response {
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig",[
            'categories' => $categories
        ]);      
    }
    /**
     * @Route("/admin/categorie/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Response
     */
    public function suppr(Categorie $categorie):Response{
        $em= $this->getDoctrine()->getManager();
        $formations= $categorie->getFormations();
         if(Count($formations)>0){
             $this->addFlash('erreur', 'impossible de supprimer cette categorie elle est ratachée a une formation ou plus!!');
            // throw $this->createNotFoundException('impossible de supprimer cette categorie car elle contient une formation ou plus!!');
         }
         else{
             $em->remove($categorie);
             $em->flush();
            $this->categorieRepository->remove($categorie, true);
            return $this->redirectToRoute('admin.categories');
         } 
    }
    /**
     * @Route("/admin/categorie/ajout", name="admin.categorie.ajout")
     * @param Request $request
     * @param EntityManagerInterface $em 
     * @return Response
     */
    public function ajout(Request $request, EntityManagerInterface $em):Response {        
        $nameCategorie = $request->query->get('name');
        $existCategorie= $em->getRepository(Categorie::class)->findOneBy(['name'=>$nameCategorie]);
        if($existCategorie){
            return new Response('cette categorie existe deja!!');
        }
        else{
            $categorie = new Categorie();
            $categorie->setName($nameCategorie);
            $em->persist($categorie);
            $em->flush();
            $this->categorieRepository->add($categorie, true);
            return $this->redirectToRoute('admin.categories');   
        }     
    }  
}
