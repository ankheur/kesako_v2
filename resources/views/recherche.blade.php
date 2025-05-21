<x-front-layout :domaines="$domaines">
    <x-recherche-breadcrumb :query="$query" />

    <section class="doc_blog_grid_area">
        <div class="container">
            <div class="text-center mt-5">
                <h2 class="wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">Les résultats pour : {{ $query }}</h2>
            </div>
            <div class="row blog_grid_tab">
                @foreach($fiches as $fiche)
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
                                        <img src="{{ Storage::url($fiche->illustration) }}" width="100">
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-front-layout>
