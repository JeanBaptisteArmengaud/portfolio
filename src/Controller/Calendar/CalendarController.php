<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar\Movie;
use App\Entity\Calendar\Show;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar', host: 'progcine.jbarmengaud.com')]
    public function index(ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $showsList = $doctrine->getRepository(Show::class)->findAll();
//        dump($showsList);
        $moviesList = $doctrine->getRepository(Movie::class)->findAll();

        $events = [];
        foreach ($showsList as $show) {
            $movieDuration = $show->getMovie()->getDuration();
            $trailerDuration = $show->getTrailersDuration();
            $presentationDuration = $show->getPresentationDuration();
            $debateDuration = $show->getDebateDuration();
//            $test = new \DateInterval('PT0M');
//            dump($test);
            $versions = $show->getVersions();
            $versionsToDisplay = [];

            $showStart = $show->getShowtime();
            $showEnd = $showStart;
            $showDurations = [];
            array_push($showDurations, $movieDuration, $trailerDuration, $presentationDuration, $debateDuration);
            foreach ($showDurations as $duration) {
                $showEnd = $showEnd->add($duration);
            }

            foreach ($versions as $version) {
                $versionName = $version->getName();
                $versionsToDisplay [] = $versionName;
            }


            $event['id'] = $show->getId();
            $event['title'] = $show->getMovie()->getTitle() . ' - ' . implode(' ', $versionsToDisplay);
            $event['start'] = $showStart->format(DATE_ATOM);
            $event['end'] = $showEnd->format(DATE_ATOM);
            $event['resourceId'] = $show->getScreen()->getId();
            $event['url'] = $this->generateUrl('app_calendar_edit_show', ['id' => $show->getId()]);
//            $event['borderColor'] = 'blue';
//            $event['backgroundColor'] = 'gray';
//            $event['textColor'] = 'red';
            $events [] = $event;
        }

        $jsonEvents = json_encode($events);

        return $this->render('calendar/admin/calendar.html.twig', [
            'jsonEvents' => $jsonEvents,
        ]);
    }

}
