<?php

namespace App\Controller\Calendar\Admin;

use App\Entity\Calendar\Movie;
use App\Form\Calendar\MovieType;
use App\Repository\Calendar\MovieRepository;
use App\Service\MovieScreeningSchedules;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private $movieScreeningSchedules;

    public function __construct(MovieScreeningSchedules $movieScreeningSchedules)
    {
        $this->movieScreeningSchedules = $movieScreeningSchedules;
    }

    #[Route('/calendar/movie', name: 'app_calendar_admin_movie')]
    public function index(MovieRepository $movieRepository): Response
    {
        $moviesList = $movieRepository->findAll();

        return $this->render('calendar/admin/movie/index.html.twig', [
            'moviesList' => $moviesList
        ]);
    }

    #[Route('/calendar/movie/new', name: 'app_calendar_admin_new_movie', methods: ['GET', 'POST'])]
    public function newMovie(Request $request, MovieRepository $movieRepository): Response
    {
        $movie = new Movie();

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->movieScreeningSchedules->checkScreeningSchedules($movie);
            $movieRepository->add($movie);
            $this->addFlash('success', 'Film ajouté.');
            return $this->redirectToRoute('app_calendar_admin_movie', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/admin/movie/add_edit_movie.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    #[Route('/calendar/movie/{id}/edit', name: 'app_calendar_admin_edit_movie', methods: ['GET', 'POST'])]
    public function editMovie(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->movieScreeningSchedules->checkScreeningSchedules($movie);
            $movieRepository->add($movie);
            $this->addFlash('success', 'Film modifié.');
            return $this->redirectToRoute('app_calendar_admin_movie', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/admin/movie/add_edit_movie.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_delete_movie', methods: ['POST'])]
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {

            if ($movie->getShows() != null) {
                $this->addFlash('danger', 'Suppression du film impossible car des séances sont programmées.');
                return $this->redirectToRoute('app_calendar_admin_edit_movie', ['id' => $movie->getId()], Response::HTTP_SEE_OTHER);
            }

            $movieRepository->remove($movie);
            $this->addFlash('success', 'Film supprimé.');
        }

        return $this->redirectToRoute('app_calendar_admin_movie', [], Response::HTTP_SEE_OTHER);
    }

}
