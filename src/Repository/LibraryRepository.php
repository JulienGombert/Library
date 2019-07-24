<?php


namespace App\Repository;


use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LibraryRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
        parent::__construct($registry, Author::class);
    }


    // méthode pour trouver des auteurs en fonction d'un mot de leur biographie
// prend en parametre la chaine de caractère envoyée depuis le controleur (qui appelle cette méthode)
    public function getAuthorsByBio($word)
    {

        // je récupère le query builder, qui me permet de créer des
        // requetes SQL
        $qb = $this->createQueryBuilder('a');

        // je sélectionne tous les auteurs de la base de données
        $query = $qb->select('a', 'b')
            ->join(Book::class, 'b')
            // si le 'word' est trouvé dans la biographie
            ->where('a.bio LIKE :word')
            ->andWhere('b.resume LIKE :word')
            // j'utilise le setParameter pour sécuriser la requete
            ->setParameter('word', '%' . $word . '%')
            // je créé la requete SQL
            ->getQuery();

        // je récupère les résultats sous forme d'array
        $resultats = $query->getArrayResult();

        return $resultats;

    }




}