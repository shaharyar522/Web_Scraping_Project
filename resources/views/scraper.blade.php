<h2>Scraped Testimonials</h2>

@foreach ($items as $item)
    <div style="border:1px solid #ccc;padding:15px;margin-bottom:10px;">
        <img src="{{ asset($item->image) }}" width="80"><br><br>
        <strong>{{ $item->name }}</strong><br>
        <em>{{ $item->title }}</em><br>
        <p>{{ $item->text }}</p>
    </div>
@endforeach
