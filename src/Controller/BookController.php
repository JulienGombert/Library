<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{

    /**
     * @Route("/admin/booklist", name="admin_book_list")
     *
     * Je passe en parametre la classe "EntityManagerInterface" avec la variable
     * $entityManager, pour que Symfony mette dans la variable une instance de la
     * classe
     */
    public function bookList(EntityManagerInterface $entityManager)
    {

        // j'utilise l'instance de la classe entity Manager, pour récupérer
        // le répository des Book.
        // j'ai besoin du repository pour faire des requetes SELECT dans la table
        $bookRepository = $entityManager->getRepository(Book::class);

       /* dump($books); die;*/

        return $this->render('admin/admin.bookList.html.twig', [
                // j'utilise la méthode findAll du repository pour récupérer tous mes Books
                'books' => $bookRepository->findAll()
        ]);
    }



    /* Attention voici 2 façons d'écrire la mm chose :
    1 : via EntityManagerInterface avec instanciation $entityManager
    2 : via les repositories directement (ici les getRepository sont déjà intégrées
    */

    /**
     * @Route("/book/show/{id}", name="book_show")
     */
    public function bookShow(BookRepository $bookRepository, $id)
    {
        // j'utilise la méthode find du BookRepository afin
        // de récupérer un livre dans la table Book en fonction
        // de son id

        return $this->render('book.html.twig', [
                'book' => $bookRepository->find($id)
        ]);
    }

    /**
     * @Route("/book/genre", name="book_genre")
     */
    public function booksByStyle(BookRepository $bookRepository)
    {
        return $this->render('book.genre.html.twig', [
            'books' =>$bookRepository->findByGenre(),
            'type' => 'roman'
        ]);
        /*$books = $bookRepository->findByGenre();
        var_dump($books); die;*/
    }

    // je veux rechercher et afficher les livres dont le résumé contient le mot : " "
    /**
     * @Route("/books/resume", name="books_resume")
     */
    public function bookByResume(BookRepository $bookRepository, Request $request)
    {
        $word = $request->query->get('word');

        return $this->render('books.name.resume.html.twig',[
            'book' => $bookRepository->getBooksByResume($word)
        ]);
    }

    /*Pour l'insertion de données dans ma table BOOK je vais incrémenter mon ENTITY pour que cela soit ensuite envoyé dan la BDD */
    /**
     * @Route("/book/insert", name="book_insert")
     */
    public function insertBook(EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
       /* je récupère une entité pour aller récupérer et setter une foreign key au nouveau livre*/
        $author = $authorRepository->find(1);

        $book = new Book();
        $book->setTitle('L\'Etranger');
        $book->setGenre('Roman');
        $book->setNumberpages(159);
        $book->setResume('Le roman met en scène un personnage-narrateur nommé Meursault, vivant à Alger en Algérie française. Le roman est découpé en deux parties.

Au début de la première partie, Meursault reçoit un télégramme annonçant que sa mère, qu\'il a internée à l’hospice de Marengo, vient de mourir. Il se rend en autocar à l’asile de vieillards, situé près d’Alger. Veillant la morte toute la nuit, il assiste le lendemain à la mise en bière et aux funérailles, sans avoir l\'attitude attendue d’un fils endeuillé ; le héros ne pleure pas, il ne veut pas simuler un chagrin qu\'il ne ressent pas.

Le dimanche midi, après un repas bien arrosé, Meursault, Raymond et Masson se promènent sur la plage et croisent deux Arabes, dont le frère de la maîtresse de Raymond. Meursault, apprenant que Raymond est armé, lui demande de lui confier son revolver pour éviter un drame. Une bagarre éclate, au cours de laquelle Raymond est blessé au visage d\'un coup de couteau. Plus tard, Meursault, seul sur la plage accablé de chaleur et de soleil, rencontre à nouveau l’un des Arabes, qui, à sa vue, sort un couteau. Aveuglé par la sueur, ébloui par le reflet du soleil sur la lame, Meursault tire de sa poche le revolver que Raymond lui a confié et tue l\'Arabe d\'une seule balle. Puis, sans raison apparente, il tire quatre autres coups sur le corps inerte.

Dans la seconde moitié du roman, Meursault est arrêté et questionné. Ses propos sincères et naïfs mettent son avocat mal à l\'aise. Il ne manifeste aucun regret, mais de l\'ennui. Lors du procès, on l\'interroge davantage sur son comportement lors de l\'enterrement de sa mère que sur le meurtre. Meursault se sent exclu du procès. Il dit avoir commis son acte à cause du soleil, ce qui déclenche l\'hilarité de l\'audience. La sentence tombe : il est condamné à la guillotine. L’aumônier visite Meursault pour qu\'il se confie à Dieu dans ses derniers instants, Meursault refuse. Quand l\'aumônier lui dit qu\'il priera pour lui, cela déclenche sa colère.

Avant son départ pour la mort, Meursault finit par trouver la paix dans la sérénité de la nuit.');

      /* j'utilise le setter d'author de l'entité book pour relier l'auteur et son livre */
        $book->setAuthor($author);

           $entityManager->persist($book);
           $entityManager->flush();

           var_dump('livre enregistré'); die;
    }


    /*________________________________*/
    /*Pour la suppression de données*/

    /**
     * @Route("/book/{id}/delete", name="book_delete")
     *
     * Je récupère la valeur de la wildcard {id} dans la variable id
     * Je récupère le bookRepository car j'ai besoin d'utiliser la méthode find
     * Je récupère l'entityManager car c'est lui qui me permet de gérer les entités (ajout, suppression, modif)
     */
    public function removeBook ($id, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        // je récupère le livre dans la BDD qui a l'id qui correspond à la wildcard
        // ps : c'est une entité qui est récupérée
        $book = $bookRepository->find($id);

        // j'utilise la méthode remove() de l'entityManager en spécifiant
        // le livre à supprimer
        $entityManager->remove($book);
        $entityManager->flush();

        var_dump('livre supprimé'); die;
    }



    /*Mise à jour / update de donnée*/
    /**
     * @Route("/book/{id}/update", name="book_update")
     *
     * Je récupère la valeur de la wildcard {id} dans la variable id
     * Je récupère le bookRepository car j'ai besoin d'utiliser la méthode find
     * Je récupère l'entityManager car c'est lui qui me permet de gérer les entités (ajout, suppression, modif)
     */
    public function updateBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find(4);

        // je récupère ;le livre dans la BDD qui a l'id qui correspond à la wildcard
        $book = $bookRepository->find($id);
        // j'utilise le setter du titre pour modifier le titre du livre
        $book->setNumberpages(213);

        $book->setAuthor($author);

        // je re-enregistre le livre dans la base de données
        $entityManager->persist($book);
        $entityManager->flush();
        var_dump("livre mis à jour"); die;
    }



















}