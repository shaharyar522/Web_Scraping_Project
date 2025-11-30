<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\DemoScrape;
use Illuminate\Http\Request;

class DemoScrapeController extends Controller
{
       public function scrape()
    {
        $response = Http::get('https://books.toscrape.com/');
        $html = $response->body();

        $crawler = new Crawler($html);

        // Scrape .elementor-size-default
        $crawler->filter('.product_pod')->each(function ($node) {
             $text = $node->filter('h3 a')->attr('text');

            DemoScrape::create(['text' => $text]);
        });
        return redirect()->route('demo.index')->with('success', 'Scraping completed!');
    }

    // Show scraped data
    public function index()
    {
        $items = DemoScrape::all();
        return view('demo_scrape', compact('items'));
    }
}
