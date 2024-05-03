<?php

namespace App\Controller\Calendar\Api;

use App\Entity\Calendar\ScreeningSchedule;
use App\Repository\Calendar\MovieRepository;
use App\Repository\Calendar\ScreeningScheduleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MovieController extends AbstractController
{
    #[Route('/calendar/api/movies', name: 'movies_get_collection')]
    public function moviesGetCollection(MovieRepository $movieRepository): Response
    {
        $moviesList = $movieRepository->findAll();

        return $this->json(['movies' => $moviesList], Response::HTTP_OK, [], ['groups' => 'movies_get_collection']);
    }

    #[Route('/calendar/api/movies-by-dates', name: 'movies_get_collection_by_screening_schedule')]
    public function moviesByDates(Request $request, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), null,512, JSON_THROW_ON_ERROR);

        $start = new \DateTimeImmutable($data->start);
        $end = new \DateTimeImmutable($data->end);

        $moviesList = $doctrine->getRepository(ScreeningSchedule::class)->findOneByDates($start, $end);

        $movies = [];


        if ($moviesList === null) {
            return $this->json(Response::HTTP_NO_CONTENT);
        }

        $moviesList = $moviesList->getMovies();
        foreach ($moviesList as $movieObject) {
            $movie['id'] = $movieObject->getId();
            $movie['title'] = $movieObject->getTitle();
            $movie['duration'] = $movieObject->getDuration()->format('%H:%I');
            $movie['extendedProps']['poster'] = $movieObject->getPoster();
            $movies [] = $movie;
        }

        return $this->json($movies, Response::HTTP_OK);

    }
}
