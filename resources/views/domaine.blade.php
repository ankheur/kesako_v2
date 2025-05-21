<x-front-layout :domaines="$domaines">
    <x-domaine-breadcrumb :domaine="$domaine"/>

    <section class="doc_documentation_area" id="sticky_doc">
        <div class="overlay_bg"></div>
        <div class="container custom_container">
            <div class="row">
                <div class="col-12 doc-middle-content">
                    <fiche id="post" class="shortcode_info documentation_info tour_content">
                        <div class="documentation_body pt-2 pt-md-5" id="documentation">
                            <div class="row">
                                <div class="col-md-12 d-md-flex flex-md-column justify-content-md-center shortcode_title text-center">
                                    <div class="d-md-flex flex-row align-items-center justify-content-center mb-5">
                                        <img src="{{ Storage::url($domaine->icone) }}" height="80" class="pr-md-5">
                                        <h1 class="mb-0">{{ $domaine->titre }}</h1>
                                    </div>

                                    <blockquote class="media notice text-left mr-2">
                                        {!! $domaine->description !!}
                                    </blockquote>
                                </div>
                            </div>
                            {{--<div class="shortcode_title text-center">
                                @foreach($types_categorie as $type)
                                    <button class="btn">{{ $type->getLabel() }}</button>
                                @endforeach
                            </div>--}}
                            <div x-data="{ type: '' }" class="text-center space-y-4">
                                <div class="shortcode_title flex gap-2">
                                    @foreach($types_categorie as $type)
                                        <button
                                            @click="type = '{{ $type->value }}'"
                                            class="btn"
                                        >{{ $type->getLabel() }}</button>
                                    @endforeach
                                    <div class="space-y-2">
                                        @foreach($categories as $category)
                                            <a class="btn" x-show="type === '{{ $category->type }}'">{{ $category->denomination }}</a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <div class="row blog_grid_tab mb-5 pt-2">
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
                                                        <img src="{{ Storage::url($fiche->illustration) }}" width="100" alt="{{ $fiche->alt_illustration }}">
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
                    </fiche>
                </div>
            </div>
        </div>
    </section>

</x-front-layout>
