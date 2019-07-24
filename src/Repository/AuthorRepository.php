<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * Je créé une nouvelle requête en base de données, pour
     * récupérer les auteurs
     *
     * (requete test, car la méthode existe déjà dans le repo)
     */
    public function findAuthorByName()
    {
        $lastname = 'Camus';
        // je récupère le query builder de Doctrine pour créer la requête
        $qb = $this->createQueryBuilder('a');
        // je viens sélectionner tous les éléments
        // de la table Book
        $query = $qb->select('a')
            // je fais ma conditions WHERE
            // je lui demande de récupérer uniquement
            // les livres dont la colonne style correspond
            // à la valeur de la variable $style
            ->where('a.lastName = :lastName')
            // j'utilise les parametres pour sécuriser la variable
            // $style et éviter les attaques
            ->setParameter('lastName', $lastname)
            // je créé la requete SQL équivalente
            ->getQuery();
        // je récupère les résultats sous forme d'array
        $resultats = $query->getArrayResult();
        // je retourne les résultats
        return $resultats;
    }


    // méthode pour trouver des auteurs en fonction d'un mot de leur biographie
    // afficher la variable
    public function getAuthorsByBio($word)
    {
        // LIGNE A MODIFIER, VALEUR A RECUPERER DEPUIS L'URL (DONC A TRAITER DANS LE CONTROLEUR)
        /*$word = 'explorateur';*/
        // je récupère le query builder, qui me permet de créer des
        // requetes SQL
        $qb = $this->createQueryBuilder('a');
        // je sélectionne tous les auteurs de la base de données
        $query = $qb->select('a')
            // si le 'word' est trouvé dans la biographie
            ->where('a.bio LIKE :word')
            // j'utilise le setParameter pour sécuriser la requete
            ->setParameter('word', '%'.$word.'%')
            // je créé la requete SQL
            ->getQuery();
        // je récupère les résultats sous forme d'array
        $resultats = $query->getArrayResult();
        return $resultats;
    }


    /*Création de la requête pour stocker les 2 derniers auteurs rentrés dans la BDD*/
    public function getTheLastTwoAuthors()
    {
        $qb = $this->createQueryBuilder('a');

        $query = $qb
            ->select('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(2)
            ->getQuery();

        $resultat = $query->getArrayResult();

        return $resultat;
    }


    // /**
    //  * @return Author[] Returns an array of Author objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
