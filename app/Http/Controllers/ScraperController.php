<?php

namespace App\Http\Controllers;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use App\Models\Testimonial;

class ScraperController extends Controller
{
    public function scraper()
    {
        // Get HTML from the website
        $response = Http::get('https://webtechfusion.pk/');
        $html = $response->body();

        // Load HTML into Crawler
        $crawler = new Crawler($html);

        $crawler->filter('.techex--t-single')->each(function ($node) {
            $text = $node->filter('p')->text('');
            $name = $node->filter('.techex--tn-name')->text('');
            $title = $node->filter('.techex--tn-title')->text('');

            $img = $node->filter('.wp-post-image')->attr('src');
            $imgName = basename($img);

            //Save image locally
            file_put_contents(public_path("scraped/$imgName"), file_get_contents($img));

            // Save to DB
            Testimonial::create([
                'name' => $name,
                'title' => $title,
                'text' => $text,
                'image' => "scraped/$imgName"
            ]);
        });
    }


    public function showTestimonials()
    {
        $items = Testimonial::get();

        return view('scraper', compact('items'));
    }
}
