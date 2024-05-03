<?php

namespace App\DataFixtures;

use App\Entity\Calendar\Movie;
use App\Entity\Calendar\Screen;
use App\Entity\Calendar\Version;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $versions = [
            'VF',
            'VF AD',
            'VFSTF',
            'VOST',
            '3D',
        ];

        foreach ($versions as $version) {
            $newVersion = new Version();
            $newVersion->setName($version);
            $manager->persist($newVersion);
        }
        for ($i = 1 ; $i <= 2 ; $i++) {
            $newScreen = new Screen();
            $newScreen->setName('Salle ' . $i);
            $newScreen->setCapacity(100);
            $manager->persist($newScreen);
        }

        // On récupère une liste de films depuis l'API MovidDb et on la convertit du format JSON vers un tableau d'objets
        $movies = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/popular?api_key=816d19bf7890f4fe2e7c0e3e750c20c5&language=fr-FR&page=2'));


        // On stocke dans une variable le préfixe de l'URL pour pouvoir stocker le poster de chaque film en BDD
        $posterBaseURL = 'https://image.tmdb.org/t/p/w500';

        // Pour chaque résultat de la liste
        foreach ($movies->results as $movieJSON) {

            // On récupère l'id du film
            $movieIdJSON = $movieJSON->id;
            // On customise l'URL de requête API en fonction de l'ID du film/série
            $movieEndpoint = 'https://api.themoviedb.org/3/movie/' . $movieIdJSON . '?api_key=816d19bf7890f4fe2e7c0e3e750c20c5&&language=fr-FR&append_to_response=credits';

            // On lance une nouvelle requête pour chaque film afin d'obtenir les détails et le casting
            $movieDetails = json_decode(file_get_contents($movieEndpoint));

            $movieRuntime = $movieDetails->runtime;
            $movieDuration = new \DateInterval('PT' . intdiv($movieRuntime, 60) . 'H' . ($movieRuntime % 60) . 'M');


            // On instancie un nouvel objet Movie et on y assigne les informations souhaitées
            $newMovie = new Movie();
            $newMovie->setTitle($movieDetails->title);
            $newMovie->setReleaseDate(new \DateTimeImmutable($movieDetails->release_date));
            $newMovie->setDuration($movieDuration);
            $newMovie->setPoster($posterBaseURL . $movieDetails->poster_path);

            $manager->persist($newMovie);
        }

        $manager->flush();
    }
}
