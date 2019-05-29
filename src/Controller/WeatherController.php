<?php


namespace App\Controller;

use App\Entity\Forecast;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $response = $client->request('GET', 'http://localhost:4000/graphql?query={forecast(city:"casablanca"){day date low high text code}}');

        $forecast = $response->getBody()->getContents();
        $forecast = json_decode($forecast);


        return $this->render('index.html.twig', [
            'forecast' => $forecast,
            'cities' => $this->cities,
        ]);
    }


    /**
     * @Route("/weather/{city}", name="weather_submit_city", methods={"POST"})
     */
    public function submitCity($city)
    {
//        $client = new Client();
//        $response = $client->request('GET', 'https://weather-ydn-yql.media.yahoo.com/forecastrss?location=casablanca,ca&format=json');
//
//        $forecast = $response->getBody()->getContents();
//        $forecast = json_decode($forecast);


        $url = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
        $app_id = 'SYNw0K64';
        $consumer_key = 'dj0yJmk9WU5yQTFZSktsRGJVJnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PTVj';
        $consumer_secret = 'bc45b8fd24cc0a84c3b27a7fd88d34933c2e51ec';

        $query = array(
            'location' => $this->cities[$city],
            'format' => 'json',
        );

        $oauth = array(
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => uniqid(mt_rand(1, 1000)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );

        $base_info = Forecast::buildBaseString($url, 'GET', array_merge($query, $oauth));
        $composite_key = rawurlencode($consumer_secret) . '&';
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $header = array(
            Forecast::buildAuthorizationHeader($oauth),
            'X-Yahoo-App-Id: ' . $app_id
        );
        $options = array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $url . '?' . http_build_query($query),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        $return_data = json_decode($response);
        $forecasts = $return_data->forecasts;

        foreach ($forecasts as $forecast){
            $forecast->date = date('m/d/Y H:i:s', $forecast->date);
        }

//        var_dump($forecasts[0]);
//        die();
        return $this->json($response);

//        return $this->render('index.html.twig', [
//            'forecasts' => $forecasts,
//            'cities' => $this->cities
//        ]);
    }
}