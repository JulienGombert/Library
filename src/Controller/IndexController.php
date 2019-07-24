<?php


namespace App\Controller;


use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */

    public function showIndex(AuthorRepository $authorRepository, BookRepository $bookRepository)
    {


        $twolastauthors = $authorRepository->getTheLastTwoAuthors();

        $twolastbooks = $bookRepository->getTheLastTwoBooks();


        return $this->render('index.html.twig', [
            'authors' => $twolastauthors,
            'books' => $twolastbooks
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function showContact()
    {
        return $this->render('contact.html.twig');
    }



    /*Je veux rechercher les auteurs dont un mot dans la bio est checké ou les livres dont le mot est checké dans le résumé*/
    /**
     * @Route("/totalsearch", name="total_search")
     */
    public function totalSearch()
    {

        return $this->render('bases/_header.html.twig');

    }


}