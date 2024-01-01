<x-front-layout :categories="$categories">
    <section class="doc_features_area">
        {{--<img class="doc_features_shap" src="{{ asset('build/img/new/shap_white.png') }}" alt="">--}}
        <div class="container">
            <div class="doc_features_inner">
                @foreach($categories as $categorie)
                    <div class="media doc_features_item wow fadeInUp align-items-center" data-wow-delay="0.1s" data-wow-duration="0.5s">
                        <img width="50" height="50" src="{{ Storage::url($categorie->icone) }}" alt="icone categorie {{ $categorie->titre }}">
                        <div class="media-body">
                            <a href="{{ route('categorie.show', $categorie->slug) }}">
                                <h4>{{ $categorie->titre }}</h4>
                            </a>
                            {{--<p>245 Posts</p>--}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="doc_blog_grid_area">
        <div class="container">
            <div class="text-center">
                <h2 class="wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">Les résultats pour : {{ $query }}</h2>
            </div>
            <div class="row blog_grid_tab">
                @foreach($articles as $article)
                    <div class="col-lg-4 col-sm-6">
                        <div class="blog_grid_post shadow-sm wow fadeInUp">
                            <a href="{{ route('article.show', $article->slug) }}">
                                <img src="{{ asset('img/blog-grid/blog_grid_post1.jpg') }}" alt="">
                                <div class="grid_post_content d-flex justify-content-between">
                                    <div>
                                        <div class="post_tag">
                                            {{--<span>18 Min Read</span>--}}
                                            <span>{{ $article->categorie->titre }}</span>
                                        </div>
                                        <h4 class="b_title">{{ $article->titre }}</h4>
                                        <p>{!! Str::limit($article->description, 50) !!}</p>
                                    </div>
                                    <div>
                                        <img src="{{ Storage::url($article->illustration) }}" width="100">
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
