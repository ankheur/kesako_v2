<x-front-layout :categories="$categories">
    <x-categorie-breadcrumb :categorie="$categorie"/>

    <section class="doc_documentation_area" id="sticky_doc">
        <div class="overlay_bg"></div>
        <div class="container custom_container">
            <div class="row">
                <div class="col-lg-12 col-md-8 doc-middle-content">
                    <article id="post" class="shortcode_info documentation_info tour_content">
                        <div class="documentation_body" id="documentation">
                            <div class="row">
                                <div class="col-md-12 d-flex flex-column justify-content-center shortcode_title text-center">
                                    <div class="d-flex flex-row align-items-center justify-content-center mb-5">
                                        <img src="{{ Storage::url($categorie->icone) }}" height="80" class="pr-5">
                                        <h1 class="mb-0">{{ $categorie->titre }}</h1>
                                    </div>

                                    <blockquote class="media notice notice-warning text-left">
                                        {!! $categorie->description !!}
                                    </blockquote>
                                </div>
                            </div>
                            <div class="row blog_grid_tab mb-5">
                                @foreach($categorie->articles as $article)
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
                        <footer>
                            <div class="border_bottom"></div>
                            <div class="row feedback_link">
                                <div class="col-lg-6">
                                    <h6><i class="icon_mail_alt"></i>Vous souhaitez corriger ou ajouter une information ? <a href="#" data-toggle="modal" data-target="#exampleModal3">Comment contribuer ?</a></h6>
                                </div>
                                <div class="col-lg-6">
                                    <p>Cette page vous a t'elle été utile ? <a href="#" class="h_btn">Oui</a><a href="#" class="h_btn red">Non</a></p>
                                </div>
                            </div>
                        </footer>
                    </article>
                </div>
            </div>
        </div>
    </section>

</x-front-layout>
