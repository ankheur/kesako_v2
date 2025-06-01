<x-front-layout :domaines="$domaines">
    <x-cat-breadcrumb :cat="$type->getLabel()"/>

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
                                        <h1 class="mb-0">{{ $type->getLabel() }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row blog_grid_tab mb-5 pt-2">
                                @foreach($fiches as $fiche)
                                    <livewire:miniature-fiche :$fiche :key="$fiche->id">
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
