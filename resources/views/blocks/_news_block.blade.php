<a name="news"></a>
<div class="image-block" data-scroll="news">
    <div class="container slide">
        <h1>Новости</h1>
        @if (count($data['news']) || $data['important_news'])
            <div class="{{ count($data['news']) > 1 ? 'col-md-6 col-sm-6' : 'col-md-12 col-sm-12' }} col-xs-12 news-container">
                <div class="news-content">
                    <div class="date">{{ date('d.m.Y',$data['important_news']->time) }}</div>
                    <h2>{{ $data['important_news']->head }}</h2>
                    @if ($data['important_news']->image)
                        <div class="image col-md-6 col-sm-12 col-xs-12"><a href="{{ $data['important_news']->image }}" class="img-preview"><img src="{{ $data['important_news']->image }}" /></a></div>
                    @endif
                    {!! $data['important_news']->text !!}
                </div>
            </div>
            @if (count($data['news']) > 1)
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="news">
                        <ul id="news1" class="active">
                            @foreach($data['news'] as $k => $news)
                                {!! $k && $k%5 == 0 ? '</ul><ul id="news'.($k/5+1).'" style="display:none;">' : ''  !!}
                                <li>
                                    <div class="date">{{ date('d.m.Y',$news->time) }}</div>
                                    <h3>{{ $news->head }}</h3>
                                    <a href="/news/{{ $news->slug }}" class="link">{!! $news->short !!}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if (ceil(count($data['news'])/5) > 1)
                        <ul class="pagination">
                            @for($i=1;$i<=ceil(count($data['news'])/5);$i++)
                                <li {{ $i==1 ? 'class=active' : '' }}>{{ $i }}</li>
                            @endfor
                        </ul>
                    @endif
                </div>
            @endif
        @endif
    </div>
</div>