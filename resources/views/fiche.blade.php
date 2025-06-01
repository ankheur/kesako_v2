<x-front-layout :domaines="$domaines">
    <x-fiche-breadcrumb :fiche="$fiche"/>

    <!--================Topic Area =================-->
    <section class="doc_documentation_area" id="sticky_doc">
        <div class="overlay_bg"></div>
        <div class="container">
            <div class="row">
                <div class="d-md-flex doc-middle-content">
                    <fiche id="post" class="shortcode_info documentation_info tour_content">
                        <div class="documentation_body" id="documentation">
                            <div class="row">
                                <div class="col-lg-9 col-12 d-flex flex-column justify-content-center shortcode_title text-center">
                                    <h1>{{ $fiche->titre }}</h1>
                                    <p><strong>{{ $fiche->soustitre }}</strong></p>

                                    <div class="shortcode_title">
                                        <a href="{{ route('fiche-type.show', $fiche->type) }}" class="btn">{{ $fiche->type->getLabel() }}</a>
                                    </div>

                                    <blockquote class="media notice text-left mr-2">
                                        {!! $fiche->description !!}
                                    </blockquote>
                                </div>
                                <div class="col-lg-3 col-12 d-flex justify-content-lg-end justify-content-center mb-5 mb-lg-0">
                                    <img src="{{ Storage::url($fiche->illustration) }}" height="300" alt="{{ $fiche->alt_illustration }}">
                                </div>
                            </div>

                            @if($fiche->portfolio)
                                @foreach($fiche->portfolio as $block)
                                    @includeIf('blocks.' . $block['type'], ['data' => $block['data']])
                                @endforeach
                            @endif


                            <div>
                                {!! $fiche->contenu !!}
                            </div>

                            @if($fiche->categories)
                                <div class="shortcode_title">
                                    @foreach($fiche->categories as $categorie)
                                        <a href="{{ route('categorie.show', $categorie->slug) }}" class="btn">{{ $categorie->denomination }}</a>
                                    @endforeach
                                </div>
                            @endif
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
    <!--================End Topic Area =================-->
</x-front-layout>
