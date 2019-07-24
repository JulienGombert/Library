<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Je créé une nouvelle requête en base de données, pour
     * récupérer tous les livres en fonction
     *
     * (requete test, car la méthode existe déjà dans le repo)
     */
    public function findByGenre()
    {
        $genre = 'Roman';
        // je récupère le query builder de Doctrine pour créer la requête
        $qb = $this->createQueryBuilder('b');
        // je viens sélectionner tous les éléments
        // de la table Book
        $query = $qb->select('b')
            // je fais ma conditions WHERE
            // je lui demande de récupérer uniquement
            // les livres dont la colonne style correspond
            // à la valeur de la variable $style
            ->where('b.genre = :genre')
            // j'utilise les parametres pour sécuriser la variable
            // $style et éviter les attaques
            ->setParameter('genre', $genre)
            // je créé la requete SQL équivalente
            ->getQuery();
        // je récupère les résultats sous forme d'array
        $resultats = $query->getArrayResult();
        // je retourne les résultats
        return $resultats;
    }

    // méthode pour trouver des livres en fonction d'un mot de leur resume
    // afficher la variable
    public function getBooksByResume($word)
    {
        // LIGNE A MODIFIER, VALEUR A RECUPERER DEPUIS L'URL (DONC A TRAITER DANS LE CONTROLEUR)
        /*$word = 'explorateur';*/
        // je récupère le query builder, qui me permet de créer des
        // requetes SQL
        $qb = $this->createQueryBuilder('b');
        // je sélectionne tous les auteurs de la base de données
        $query = $qb->select('b')
            // si le 'word' est trouvé dans la biographie
            ->where('b.resume LIKE :word')
            // j'utilise le setParameter pour sécuriser la requete
            ->setParameter('word', '%'.$word.'%')
            // je créé la requete SQL
            ->getQuery();
        // je récupère les résultats sous forme d'array
        $resultats = $query->getArrayResult();
        return $resultats;
    }


    /*Création de la requête pour sélectionner les 2 derniers livres*/
    public function getTheLastTwoBooks()
    {
        $qb = $this->createQueryBuilder('b');
        $query = $qb

            //
            ->leftJoin('b.author', 'a')

            ->addSelect('a')

            ->orderBy('b.id', 'DESC')
            ->setMaxResults(2)
            ->getQuery();

        $resultat = $query->getArrayResult();
        return $resultat;
    }


    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
