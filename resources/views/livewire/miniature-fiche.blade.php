<div class="col-lg-4 col-sm-6">
    <div class="blog_grid_post shadow-sm wow fadeInUp">
        <a href="{{ route('fiche.show', $fiche->slug) }}">
            <img src="{{ asset('img/blog-grid/blog_grid_post1.jpg') }}" alt="">
            <div class="grid_post_content d-flex justify-content-between">
                <div>
                    <div class="post_tag">
                        {{--<span>18 Min Read</span>--}}
                        {{--<span>{{ $fiche->domaine->titre }}</span>--}}
                    </div>
                    <h4 class="b_title">{{ $fiche->titre }}</h4>
                    <p>{!! Str::limit($fiche->description, 50) !!}</p>
                </div>
                <div>
                    <img src="{{ Storage::url($fiche->illustration) }}" width="100" alt="{{ $fiche->alt_illustration }}">
                </div>
            </div>
        </a>
    </div>
</div>
