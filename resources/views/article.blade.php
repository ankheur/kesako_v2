<x-front-layout :categories="$categories">
    <x-article-breadcrumb :article="$article"/>

    <!--================Topic Area =================-->
    <section class="doc_documentation_area" id="sticky_doc">
        <div class="overlay_bg"></div>
        <div class="container custom_container">
            <div class="row">
                <div class="d-flex col-lg-12 col-md-8 doc-middle-content">
                    <article id="post" class="shortcode_info documentation_info tour_content">
                        <div class="documentation_body" id="documentation">
                            <div class="row">
                                <div class="col-md-9 d-flex flex-column justify-content-center shortcode_title text-center">
                                    <h1>{{ $article->titre }}</h1>
                                    <p><strong>{{ $article->soustitre }}</strong></p>

                                    <blockquote class="media notice notice-warning text-left">
                                        {!! $article->description !!}
                                    </blockquote>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end">
                                    <img src="{{ Storage::url($article->illustration) }}" height="300">
                                </div>
                            </div>


                            {{--<div class="border_bottom"></div>
                            <div class="tour_item last_tour_item">
                                <h4 class="c_head load-order-2">Tags make it easy to find</h4>
                                <div class="row align-items-center">
                                    <div class="col-sm-4 tour_info_content">
                                        <p>All questions are tagged with their subject areas. Each can have up to 5 tags, since a question might be related to several subjects.</p>
                                        <div class="arrow text-right wow fadeInLeft" data-wow-delay="0.6s">
                                            <img src="img/arrow_bottom.png" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="tour_preview_img wow fadeInRight" data-wow-delay="0.4s">
                                            <img class="img-fluid" src="img/tour_img4.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                            <div>
                                {!! $article->contenu !!}
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
    <!--================End Topic Area =================-->
</x-front-layout>
