<?php

namespace App\Repository;

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
    
    /**
     * Retourne toutes les playlists triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderBy($champ, $ordre): array{
        return $this->createQueryBuilder('p')
                ->select(self::PIDID)
                ->addSelect(self::PNAME)
                ->addSelect(self::CATEGORIENAME)
                ->leftjoin('p.formations', 'f')
                ->leftjoin('f.categories', 'c')
                ->groupBy('p.id')
                ->addGroupBy('c.name')
                ->orderBy('p.'.$champ, $ordre)
                ->addOrderBy('c.name')
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
            return $this->findAllOrderBy('name', 'ASC');
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
                    ->select(self::PIDID)
                    ->addSelect(self::PNAME)
                    ->addSelect(self::CATEGORIENAME)
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->addGroupBy('c.name')
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy('c.name')
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
                    ->select(self::PIDID)
                    ->addSelect(self::PNAME)
                    ->addSelect(self::CATEGORIENAME)
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->addGroupBy('c.name')
                    ->orderBy('p.name', 'ASC')
                    ->addOrderBy('c.name')
                    ->getQuery()
                    ->getResult(); 
        
    }
    


    
}
