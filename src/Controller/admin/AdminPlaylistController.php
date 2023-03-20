<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\FormationType;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Description of AdminPlaylistController
 *
 * @author soumia
 */
class AdminPlaylistController extends AbstractController {
     /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    /**
     * 
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    function __Construct(PlaylistRepository $playlistRepository, CategorieRepository $categorieRepository, FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    /**
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request, EntityManagerInterface $entityManager):Response{
        $playlist= new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted()&& $formPlaylist->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($playlist);
            $em->flush();
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute('admin.playlists');
        }
       return $this->render("admin/admin.playlist.ajout.html.twig",[
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
     /**
      * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
      * @param Playlist $playlist
      * @param Request $request
      * @return Response
      */
    public function edit(Playlist $playlist, Request $request):Response{
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted()&& $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute('admin.playlists');
               
        }
        return $this->render("admin/admin.playlist.edit.html.twig",[
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
             
    }
    /**
     * @Route("/admin/playlist/suppr/{id}", name="admin.playlist.suppr")
     * @param Playlist $playlist
     * @return Response
     */
    
     public function suppr(Playlist $playlist):Response{         
         $formations= $playlist->getFormations();
         if(Count($formations)>0){
             return $this->render("erreur.html.twig",[
            'erreur' => 'impossible de supprimer cette playlist car elle est ratachée a une formation ou plus!!'
        ]);
         }
         $entityManager= $this->getDoctrine()->getManager();
         $entityManager->remove($playlist);
         $entityManager->flush();
         return $this->redirectToRoute('admin.playlists');           
     }          
}
    
    
    

