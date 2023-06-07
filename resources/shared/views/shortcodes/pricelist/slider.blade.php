<h1>{{ $priceList->post_title }}</h1>

<ul>
    @foreach ($priceList->sections() as $section)
    <li>
        <h2>{{ $section->post_title }}</h2>
        <ul>
            @foreach ($section->items() as $item)
            <li>
                <h3>{{ $item->post_title }}</h3>
                <p>{{ $item->post_content }}</p>
                <ul>
                    @foreach ($item->tags() as $tag)
                    <li>{{ $tag }}</li>
                    @endforeach
                </ul>
                <ul>
                    @foreach ($item->images() as $image)
                    <li>
                        <img src="{{ $image['thumb'] }}" />
                    </li>
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </li>
    @endforeach
</ul>
