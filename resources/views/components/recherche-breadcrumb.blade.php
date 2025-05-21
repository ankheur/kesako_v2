<section class="page_breadcrumb">
    <div class="container custom_container">
        <div class="row">
            <div class="col-sm-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pages.home') }}">Accueil</a></li>
                        {{--<li class="breadcrumb-item"><a href="{{ route('domaine.show', $fiche->domaine) }}">{{ $fiche->domaine->titre }}</a></li>--}}
                        <li class="breadcrumb-item active" aria-current="page">{{ $query }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
