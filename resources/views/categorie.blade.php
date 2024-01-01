<x-front-layout :categories="$categories" :popular-posts="$popularPosts">
    <x-categorie-breadcrumb :categorie="$categorie"/>

    <section class="doc_documentation_area" id="sticky_doc">
        <div class="overlay_bg"></div>
        <div class="container custom_container">
            <div class="row">
                <div class="col-12 doc-middle-content">
                    <article id="post" class="shortcode_info documentation_info tour_content">
                        <div class="documentation_body pt-2 pt-md-5" id="documentation">
                            <div class="row">
                                <div class="col-md-12 d-md-flex flex-md-column justify-content-md-center shortcode_title text-center">
                                    <div class="d-md-flex flex-row align-items-center justify-content-center mb-5">
                                        <img src="{{ Storage::url($categorie->icone) }}" height="80" class="pr-md-5">
                                        <h1 class="mb-0">{{ $categorie->titre }}</h1>
                                    </div>

                                    <blockquote class="media notice text-left mr-2">
                                        {!! $categorie->description !!}
                                    </blockquote>
                                </div>
                            </div>
                            <div class="row blog_grid_tab mb-5 pt-2">
                                @foreach($categorie->articles as $article)
                                    <div class="col-lg-4 col-12">
                                        <div class="blog_grid_post shadow-sm wow fadeInUp">
                                            <a href="{{ route('article.show', $article->slug) }}">
                                                <img src="{{ asset('img/blog-grid/blog_grid_post1.jpg') }}" alt="">
                                                <div class="grid_post_content d-md-flex justify-content-md-between align-items-md-center">
                                                    <div class="col-md-6 px-0">
                                                        <div class="post_tag text-md-center">
                                                            {{--<span>18 Min Read</span>--}}
                                                            <span>{{ $article->categorie->titre }}</span>
                                                        </div>
                                                        <h4 class="b_title text-md-center">{{ $article->titre }}</h4>
                                                        <p class="text-md-left">{!! Str::limit($article->description, 50) !!}</p>
                                                    </div>
                                                    <div class="text-center col-md-6 px-0">
                                                        <img src="{{ Storage::url($article->illustration) }}" class="article-illustration">
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{--<footer>
                            <div class="border_bottom"></div>
                            <div class="row feedback_link">
                                <div class="col-lg-6">
                                    <h6><i class="icon_mail_alt"></i>Vous souhaitez corriger ou ajouter une information ? <a href="#" data-toggle="modal" data-target="#exampleModal3">Comment contribuer ?</a></h6>
                                </div>
                                <div class="col-lg-6">
                                    <p>Cette page vous a t'elle été utile ? <a href="#" class="h_btn">Oui</a><a href="#" class="h_btn red">Non</a></p>
                                </div>
                            </div>
                        </footer>--}}
                    </article>
                </div>
            </div>
        </div>
    </section>

</x-front-layout>
