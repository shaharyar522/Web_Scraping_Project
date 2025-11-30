<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Http\Request;

class ScraperController extends Controller
{
    private  $results = array();


    public function scraper() {

        $client = new Client();
    $url = 'https://webtechfusion.pk/';
    $response = $client->request('GET', $url);

    $html = $response->getBody()->getContents();
    $crawler = new Crawler($html);

    $results = [];

    // Loop through each parent div
    $crawler->filter('.techex--tn-single.style-three.no')->each(function (Crawler $node) use (&$results) {
        $icon = $node->filter('.techex--tn-icon')->count() ? $node->filter('.techex--tn-icon')->text() : '';
        $description = $node->filter('.techex--tn-dis')->count() ? $node->filter('.techex--tn-dis')->text() : '';
        $bottom = $node->filter('.techex-tn-bottom')->count() ? $node->filter('.techex-tn-bottom')->text() : '';

        $results[] = [
            'icon' => $icon,
            'description' => $description,
            'bottom' => $bottom,
        ];
    });

    dd($results); // Display scraped data
    }

}
