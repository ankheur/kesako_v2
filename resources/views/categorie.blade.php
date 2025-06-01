<x-front-layout :domaines="$domaines">
    <x-cat-breadcrumb :cat="$categorie"/>

    <!--================Topic Area =================-->
    <section class="doc_documentation_area" id="sticky_doc">
        <div class="overlay_bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-12 doc-middle-content">
                    <categorie id="post" class="shortcode_info documentation_info tour_content">
                        <div class="documentation_body" id="documentation">
                            <div class="row">
                                <div class="col-lg-12 col-12 d-flex flex-column justify-content-center shortcode_title text-center">
                                    <h1>{{ $categorie->denomination }}</h1>

                                    @if($categorie->description)
                                    <blockquote class="media notice text-left mr-2">
                                        {!! $categorie->description !!}
                                    </blockquote>
                                    @endif
                                </div>
                            </div>
                            <div class="row blog_grid_tab mb-5 pt-2">
                                @foreach($fiches as $fiche)
                                    <livewire:miniature-fiche :$fiche :key="$fiche->id">
                                @endforeach
                            </div>
                        </div>
                    </categorie>
                </div>
            </div>
        </div>
    </section>
    <!--================End Topic Area =================-->
</x-front-layout>
