<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{




    /**
     * @Route("/admin/authorlist", name="admin_author_list")
     */
    public function authorList(EntityManagerInterface $entityManager)
    {
        $authorRepository = $entityManager->getRepository(Author::class);
        /*$authors = $authorRepository->findAll();*/

        /*dump($authors); die;*/

        return $this->render('admin/admin.authorList.html.twig', [
            'authors' => $authorRepository->findAll()
        ]);
    }


    /* Attention voici 2 façons d'écrire la mm chose :
    1 : via EntityManagerInterface avec instanciation $entityManager
    2 : via les repositories directement (ici les getRepository sont déjà intégrées
    */



    /**
     * @Route("/author/show/{id}", name="author_show")
     */
    public function authorShow(AuthorRepository $authorRepository, $id)
    {
        // j'utilise la méthode find du AuthorRepository afin
        // de récupérer un auteur dans la table Author en fonction
        // de son id
        return $this->render('author.html.twig', [
            'author' => $authorRepository->find($id)
        ]);

    }

    /**
     * @Route("/author/name", name="author_name")
     */
    public function authorByName(AuthorRepository $authorRepository)
    {
        return $this->render('author.name.html.twig', [
            'authors' =>$authorRepository->findAuthorByName(),
            'lastname' => 'Camus'
        ]);
    }

    // je veux rechercher et afficher les auteurs dont la bio contient le mot : " "
    /**
     * @Route("/authors/bio", name="authors_bio")
     */
    public function authorsByBiography(AuthorRepository $authorRepository, Request $request)
    {
        $word = $request->query->get('word');

        return $this->render('author.name.html.twig',[
            'authors' => $authorRepository->getAuthorsByBio($word)
        ]);

    }

    /**
     * @Route("/author/insert", name="author_insert")
     */

    public function insertAuthor(EntityManagerInterface $entityManager)
    {

        $author = new Author();
        $author->setBio('Né le 25 septembre 1780 à Dublin et mort dans cette même ville le 30 octobre 1824, est un romancier et dramaturge irlandais, particulièrement connu pour avoir écrit Melmoth ou l\'Homme errant, publié en 1820 et considéré aujourd\'hui comme une des œuvres les plus représentatives du roman gothique.');
        $author->setBirthDate(new \DateTime ('1780-09-25'));
        $author->setDeathDate(new \DateTime('1824-10-30'));
        $author->setFirstName('Charles Robert');
        $author->setLastName('MATURIN');

        $entityManager->persist($author);
        $entityManager->flush();

        var_dump('auteur enregistré'); die;
    }

    /*________________________________*/
    /*Pour la suppression de données*/

    /**
     * @Route("/author/{id}/delete", name="author_delete")
     *
     * Je récupère la valeur de la wildcard {id} dans la variable id
     * Je récupère le authorRepository car j'ai besoin d'utiliser la méthode find
     * Je récupère l'entityManager car c'est lui qui me permet de gérer les entités (ajout, suppression, modif)
     */
    public function removeAuthor ($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager)
    {
        // je récupère le livre dans la BDD qui a l'id qui correspond à la wildcard
        // ps : c'est une entité qui est récupérée
        $author = $authorRepository->find($id);

        // j'utilise la méthode remove() de l'entityManager en spécifiant
        // le livre à supprimer
        $entityManager->remove($author);
        $entityManager->flush();

        var_dump('auteur supprimé'); die;
    }


    /*Mise à jour de données*/
    /**
     * @Route("/author/{id}/update", name="author_update")
     *
     * * Je récupère la valeur de la wildcard {id} dans la variable id
     * Je récupère le authorRepository car j'ai besoin d'utiliser la méthode find
     * Je récupère l'entityManager car c'est lui qui me permet de gérer les entités (ajout, suppression, modif)
     */
    public function updateAuthor($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager)
    {
        /*je récupère l'auteurdans la BDD qui a l'id qui march à la wild card*/
        $author = $authorRepository->find($id);
        $author->setFirstName('Edmond 1er');
        $entityManager->persist($author);
        $entityManager->flush();

        var_dump("auteur mis à jour"); die;

    }


    /*Méthode pour afficher les livres de chaque auteur*/

    /**
     * @Route("/eachbookofauthors", name="books_de_chaque_auteur")
     */
    public function authorsList(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();
        return $this->render('authorList.html.twig',
            [
                'authors' => $authors
            ]
        );
    }





}