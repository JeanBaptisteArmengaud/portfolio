<?php

namespace App\Controller\Calendar\Api;

use App\Entity\Calendar\Show;
use App\Form\Calendar\ShowType;
use App\Repository\Calendar\MovieRepository;
use App\Repository\Calendar\ScreenRepository;
use App\Repository\Calendar\ShowRepository;
use App\Repository\Calendar\VersionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendar/api', name: 'api_calendar_shows_')]
class ShowController extends AbstractController
{
    #[Route('/shows', name: 'shows_post', methods: ['POST'])]
    public function showsPost(Request $request, ShowRepository $showRepository, ScreenRepository $screenRepository, MovieRepository $movieRepository): Response
    {
        $data = json_decode($request->getContent());

        $show = new Show();
        $show->setMovie($movieRepository->find($data->movieId));
        $show->setScreen($screenRepository->find($data->resource));
        $show->setShowtime(new \DateTimeImmutable($data->start));
        $show->setTrailersDuration(\DateInterval::createFromDateString('10 minutes'));
        $show->setPresentationDuration(\DateInterval::createFromDateString('0 minutes'));
        $show->setDebateDuration(\DateInterval::createFromDateString('0 minutes'));

        $showRepository->add($show);

        $movieDuration = $show->getMovie()->getDuration();
        $trailerDuration = $show->getTrailersDuration();
        $presentationDuration = $show->getPresentationDuration();
        $debateDuration = $show->getDebateDuration();

        $showStart = $show->getShowtime();
        $showEnd = $showStart;
        $showDurations = [];
        array_push($showDurations, $movieDuration, $trailerDuration, $presentationDuration, $debateDuration);
        foreach ($showDurations as $duration) {
            $showEnd = $showEnd->add($duration);
        }
        return $this->json(['show' => $show, 'showEnd' => $showEnd], Response::HTTP_CREATED, [], ['groups' => "shows_get_item"]);

    }

    #[Route('/shows/{id}', name: 'showtime_patch', methods: ['PATCH'])]
    public function patchShowtime(Request $request, Show $show, ShowRepository $showRepository, ScreenRepository $screenRepository): Response
    {
        $data = json_decode($request->getContent());
        $showtime = $show->getShowtime();
        $newShowtime = $data->start;
        $newScreen = $data->resource;

        if ($newScreen !== null) {
            $screen = $screenRepository->find($newScreen->id);
            $show->setScreen($screen);
        }

        $show->setShowtime($showtime->modify($newShowtime));
//        dump($data, $show);

        $showRepository->add($show);
        return $this->json([], Response::HTTP_NO_CONTENT);


//       return $this->json([], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/shows/{id}/versions', name: 'versions_patch', methods: ['PATCH'])]
    public function patchVersions(Request $request, Show $show, ShowRepository $showRepository, VersionRepository $versionRepository): Response
    {
        $data = json_decode($request->getContent());
        $versions = $show->getVersions();
        $newVersions = $data->versions;


        if ($versions !== null) {
            foreach ($versions as $version) {
                $show->removeVersion($version);
            }
        }


        if ($newVersions !== null) {
            foreach ($newVersions as $newVersion) {
                $version = $versionRepository->find($newVersion);
                $show->addVersion($version);
            }
        }

        $showRepository->add($show);

        dump($show->getVersions());
        return $this->json($show->getVersions(), Response::HTTP_OK, [], ['groups' => 'shows_get_item']);
    }
}
