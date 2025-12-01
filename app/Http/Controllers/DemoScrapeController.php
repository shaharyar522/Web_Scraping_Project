<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\DemoScrape;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DemoScrapeController extends Controller
{
    public function scrape()
    {
        DemoScrape::truncate(); // delete old records

        $response = Http::get('https://books.toscrape.com/');
        $html = $response->body();

        $crawler = new Crawler($html);

        $crawler->filter('.product_pod')->each(function ($node) {

            // Title
            $title = trim($node->filter('h3 a')->text());

            // Price
            $priceText = $node->filter('.product_price .price_color')->text();
            $price = (float) trim(str_replace('£', '', $priceText));

            // ==============================
            // 1. GET PRODUCT DETAIL PAGE URL
            // ==============================
            $relativeUrl = $node->filter('h3 a')->attr('href');
            $detailUrl = 'https://books.toscrape.com/catalogue/' . ltrim($relativeUrl, './');

            // ==============================
            // 2. FETCH DETAIL PAGE
            // ==============================
            $detailHtml = Http::get($detailUrl)->body();
            $detailCrawler = new Crawler($detailHtml);

            // ==============================
            // 3. SCRAPE FULL STOCK TEXT

            // ==============================
            // FIXED STOCK SCRAPING
            // ==============================
            $stock = 'N/A';

            if ($detailCrawler->filter('.instock.availability')->count() > 0) {
                $stockText = $detailCrawler->filter('.instock.availability')->text();
                $stock = trim(preg_replace('/\s+/', ' ', $stockText));
            }

            // Example: "In stock (20 available)"

            // Rating
            $ratingClass = $node->filter('.star-rating')->attr('class');
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

    public function index()
    {
        $items = DemoScrape::all();
        return view('demo_scrape', compact('items'));
    }

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

            fputcsv($file, ['ID', 'Title', 'Price', 'Stock', 'Star Rating', 'Created At']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->product_title,
                    $item->product_price,
                    $item->product_stock,
                    $item->product_star_rating,
                    $item->created_at,
                ]);
            }

            fclose($file);
        };

        DemoScrape::truncate();

        return Response::stream($callback, 200, $headers);
    }
}
