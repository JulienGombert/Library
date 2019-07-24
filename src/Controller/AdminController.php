<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /*________________Partie admin AUTHORS______________*/

    /*    Création de la route et de la méthode pour appeler et afficher le formulaire d'insertion d'un nouvel auteur*/
    /**
     * @Route("/admin/authors/form_insert", name="admin_author_form_insert")
     */
    public function insertAuthorForm(Request $request, EntityManagerInterface $entityManager)
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $formAuthor = $form->createView();

        // si la méthode est POST
        // si le formulaire est envoyé

        if ($request->isMethod('Post')) {

            // le formulaire récupère les infos de la requête
            $form->handleRequest($request);

            // l'entité est enregistrée puis envoyée en BDD style commit&push
            $entityManager->persist($author);
            $entityManager->flush();
        }


        return $this->render('admin/admin_author_form_insert.html.twig',[
            'formAuthor' => $formAuthor
        ]);
    }

    /*    Création de la route et de la méthode pour appeler et afficher le formulaire d'insertion et modifier la fiche d'un auteur*/
    /**
     * @Route("/admin/authors/{id}/form_udpate", name="admin_author_form_update")
     */
    public function updateAuthorForm(Request $request, EntityManagerInterface $entityManager, AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);

        $form = $this->createForm(AuthorType::class, $author);
        $formAuthor = $form->createView();

        // si la méthode est POST
        // si le formulaire est envoyé

        if ($request->isMethod('Post'))
        {

            // le formulaire récupère les infos de la requête
            $form->handleRequest($request);

            // vérif si les renseignements proposés par l'admin sont valides
            // cad correspondent aux tables (ex: varchar(255) à 255 caractères
            // maximum)
            if ($form->isValid())
            {
                // l'entité est enregistrée puis envoyée en BDD style commit&push
                $entityManager->persist($author);
                $entityManager->flush();
            };

        }

        return $this->render('admin/admin_author_form_update.html.twig',[
            'formAuthor' => $formAuthor
        ]);
    }




    /*_________________________Partie admin BOOKS__________________________*/


    /**
     * @Route("/admin/books/form_insert", name="admin_books_form_insert")
     */
    public function bookFormInsert(Request $request, EntityManagerInterface $entityManager)
    {
        // Utilisation du fichier BookType pour créer le formulaire
        // (ne contient pas encore de html)
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        // création de la view du formulaire
        $formBook = $form->createView();
        // Si la méthode est POST
        // si le formulaire est envoyé
        if ($request->isMethod('Post')) {
            // Le formulaire récupère les infos
            // de la requête
            $form->handleRequest($request);
            // on vérifie que le formulaire est valide
            if ($form->isValid()) {
                // On enregistre l'entité créée avec persist
                // et flush
                $entityManager->persist( $book );
                $entityManager->flush();
            }
        }
        return $this->render('admin/admin_book_form_insert.html.twig',
            [
                // envoie de la view du form au fichier twig
                'formBook' => $formBook
            ]
        );
    }


    /*Création route et méthode pour mise à jour des données d'un livre selon son id*/
    /**
     * @Route("/admin/books/{id}/form_update", name="admin_book_form_update")
     */
    public function updateBookForm(Request $request, EntityManagerInterface $entityManager, $id, BookRepository $bookRepository)
    {
        $book = $bookRepository->find($id);

        $form = $this->createForm(BookType::class, $book);
        $formBook = $form->createView();

        if ($request->isMethod('Post'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $entityManager->persist($book);
                $entityManager->flush();
            }
        }

        return $this->render('admin/admin_book_form_update.html.twig',[
            'formBook' => $formBook
        ]);

    }

    /*---------------Partie index ADMIN---------*/

    /**
     * @Route("/admin", name="admin")
     */
    public function showAdmin()
    {
        return $this->render('admin/admin.html.twig');
    }

}