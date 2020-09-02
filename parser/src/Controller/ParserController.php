<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Parser controller for displaying news.
 */
class ParserController extends AbstractController
{
    private const PAGE_LIMIT = 15;

    /**
     * @param NewsRepository     $newsRepository News repository for retrieving data from database.
     * @param PaginatorInterface $paginator      Realises news pagination.
     * @param Request            $request        HTTP Request.
     *
     * @return Response
     *
     * @Route("/", name="news")
     */
    public function index(NewsRepository $newsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $news = $paginator->paginate(
            $newsRepository->findAll(),
            $request->query->getInt('page', 1),
            self::PAGE_LIMIT
        );

        return $this->render('news/news.html.twig', ['news' => $news]);
    }

    /**
     * @param NewsRepository $newsRepository News repository for retrieving data from database.
     * @param string         $slug           News article external id.
     *
     * @return Response
     *
     * @Route("/{slug}", name="article_details")
     */
    public function articleDetails(NewsRepository $newsRepository, string $slug): Response
    {
        return $this->render(
            'news/news-details.html.twig',
            ['article' => $newsRepository->findOneBy(['externalId' => $slug])]
        );
    }
}
