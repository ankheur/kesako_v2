<x-front-layout :domaines="$domaines">
    <x-recherche-breadcrumb :query="$query" />

    <section class="doc_blog_grid_area">
        <div class="container">
            <div class="text-center mt-5">
                <h2 class="wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">Les résultats pour : {{ $query }}</h2>
            </div>
            <div class="row blog_grid_tab">
                @foreach($fiches as $fiche)
                    <livewire:miniature-fiche :$fiche :key="$fiche->id">
                @endforeach
            </div>
        </div>
    </section>
</x-front-layout>
