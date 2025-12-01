<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\DemoScrape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DemoScrapeController extends Controller
{
    // Scrape and store fresh data
    public function scrape()
    {
        // Delete old data before scraping
        DemoScrape::truncate(); // deletes all old records

        $response = Http::get('https://books.toscrape.com/');
        $html = $response->body();

        $crawler = new Crawler($html);

        $crawler->filter('.product_pod')->each(function ($node) {

            $title = trim($node->filter('h3 a')->text());

            // Price
            $priceText = $node->filter('.product_price .price_color')->text();
            $price = (float) trim(str_replace('£', '', $priceText));


            // Stock
            $stockText = $node->filter('p.instock.availability')->text();
            $stockText = trim(preg_replace('/\s+/', ' ', $stockText));
            preg_match('/\((\d+)\savailable\)/i', $stockText, $matches);
            $stock = isset($matches[1]) ? (int)$matches[1] : 0;


            // Rating
            $ratingClass = $node->filter('.star-rating')->attr('class'); // e.g., "star-rating Three"
            $rating = trim(str_replace('star-rating', '', $ratingClass));

            DemoScrape::create([
                'product_title' => $title,
                'product_price' => $price,
                'product_stock' => $stock,
                'product_star_rating' => $rating,
            ]);
        });



        return redirect()->route('demo.index')->with('success', 'Scraping completed!');
    }

    // Show scraped data
    public function index()
    {
        $items = DemoScrape::all();
        return view('demo_scrape', compact('items'));
    }

    // Export CSV and delete old data after download
    public function exportCsv()
    {
        $items = DemoScrape::all();

        $filename = 'scraped_data_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['ID', 'Title', 'Price', 'Stock', 'Star Rating', 'Created At'], ',', '"');

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    html_entity_decode(trim($item->product_title)),
                    $item->product_price,
                    html_entity_decode(trim($item->product_stock)),
                    html_entity_decode(trim($item->product_star_rating)),
                    $item->created_at,
                ], ',', '"');
            }

            fclose($file);
        };


        // Delete old data after CSV is generated
        DemoScrape::truncate();

        return Response::stream($callback, 200, $headers);
    }
}
