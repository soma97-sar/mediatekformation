<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    /**
     * p.id id
     */
    private const PIDID = 'p.id id';
    /**
     * p.name name
     */
    private const PNAME = 'p.name name';
    /**
     * c.name categoriename
     */
    private const CATEGORIENAME = 'c.name categoriename';
    

    public function __Construct(ManagerRegistry $registry)
    {
        parent::__Construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    // un code de vérification si la formation n'existe pas dans une autre playlist si se pose un problème...
    /// TODO : Créee deux méthodes
    // 1 - retourné un PlayList par Id
    // 2 - Ajouter Formation à la playList retournée.
    
    //Et après ajouter delete Formation in PlayList 
    /**
     * 
     * @return Formations[]
     */ 
    public function getFormationsPlaylist():Collection {
        $formations= new ArrayCollection();
        foreach ($this->playlists as $playlist){
            $formationPlaylist= $playlist->getFormation();
            foreach ($formationPlaylist as $formationPlaylist){
                if(!$formations->contains($formationPlaylist->getId())){
                    $formations[] = $formationPlaylist->getId();
                }
            }    
                
        }
        return $formations;
        
    }
    
    
  
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('p.name', $ordre)
                ->getQuery()
                ->getResult();       
    }
    /**
     * Retourne toutes les playlists triées sur le nombre de formations
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByNbFormations($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('count(f.title)', $ordre)
                ->getQuery()
                ->getResult();       
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }    
        if($table==""){      
            $this->findByValIfTableIsEmptyy($champ, $valeur);          
        }else{   
            $this->findByValIfTableIsNotEmptyy($champ, $valeur);           
        } 
        return array();
    }
    /**
     * 
     * @param type $champ
     * @param type $valeur
     * @return Playlist[]
     */
    public function findByValIfTableIsEmptyy($champ, $valeur):array {
        return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult(); 
    }
    /**
     * 
     * @param type $champ
     * @param type $valeur
     * @return Playlist[]
     */
    public function findByValIfTableIsNotEmptyy($champ, $valeur):array {
         return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult(); 
        
    }
    
}
