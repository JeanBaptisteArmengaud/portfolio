<?php

namespace App\Controller\Calendar\Admin;

use App\Entity\Calendar\Movie;
use App\Entity\Calendar\Show;
use App\Form\Calendar\ShowType;
use App\Repository\Calendar\MovieRepository;
use App\Repository\Calendar\ShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendar/show')]
class ShowController extends AbstractController
{
    #[Route('/movie/{id}', name: 'app_calendar_movie_shows')]
    public function movieShows(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        $shows = $movie->getShows();

        return $this->render('calendar/admin/show/index.html.twig', [
            'shows' => $shows,
            'movie' => $movie,
        ]);

    }

    #[Route('/new/movie/{id}', name: 'app_calendar_new_show', methods: ['GET', 'POST'])]
    public function newShow(Request $request, ShowRepository $showRepository, Movie $movie): Response
    {
        $show = new Show();
        $show->setMovie($movie);

        $form = $this->createForm(ShowType::class, $show, [
            'movie' => $movie,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $showRepository->add($show);
            $this->addFlash('success', 'Séance ajoutée.');
            return $this->redirectToRoute('app_calendar_movie_shows', ['id' => $show->getMovie()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/admin/show/add_edit_show.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_calendar_edit_show', methods: ['GET', 'POST'])]
    public function editShow(Request $request, Show $show, ShowRepository $showRepository): Response
    {
        $form = $this->createForm(ShowType::class, $show, [
            'movie' => $show->getMovie(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $showRepository->add($show);
            $this->addFlash('success', 'Séance modifiée.');
            return $this->redirectToRoute('app_calendar_movie_shows', ['id' => $show->getMovie()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/admin/show/add_edit_show.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit/versions', name: 'app_calendar_edit_show_versions', methods: ['POST'])]
    public function editShowVersions(Request $request, Show $show, ShowRepository $showRepository): Response
    {
        $form = $this->createForm(ShowType::class, $show);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $showRepository->add($show);
            $this->addFlash('success', 'Séance modifiée.');
            return $this->redirectToRoute('app_calendar_movie_shows', ['id' => $show->getMovie()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/admin/show/add_edit_show.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_delete_show', methods: ['POST'])]
    public function delete(Request $request, Show $show, ShowRepository $showRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$show->getId(), $request->request->get('_token'))) {
            $showRepository->remove($show);
            $this->addFlash('success', 'Séance supprimée.');
        }

        return $this->redirectToRoute('app_calendar_movie_shows', ['id' => $show->getMovie()->getId()], Response::HTTP_SEE_OTHER);
    }
}
