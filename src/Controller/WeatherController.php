<?php


namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    private $cities = ['casablanca', 'sunnyvale', 'chicago', 'philadelphia', 'rabat'];

    /**
     * @Route("/weather")
     */
    public function index()
    {
        $client = new Client();
//        $query = '{
//            forecast(city: "casablanca"){
//                day
//                date
//                low
//                high
//                text
//                code
//            }
//        }';
        $response = $client->request('GET', 'http://localhost:4000/graphql?query={forecast(city:"casablanca"){day date low high text code}}');

        $forecast = $response->getBody()->getContents();
        $forecast = json_decode($forecast);


        return $this->render('weather.html.twig', [
            'forecast' => $forecast,
        ]);
    }
}