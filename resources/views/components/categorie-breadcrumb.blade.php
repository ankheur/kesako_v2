<section class="page_breadcrumb">
    <div class="container custom_container">
        <div class="row">
            <div class="col-sm-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pages.home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('categorie.show', $categorie) }}">{{ $categorie->titre }}</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-5">
                <a href="#" class="date"><i class="icon_clock_alt"></i>Publié le {{ \Carbon\Carbon::parse($categorie->published_at)->isoFormat('DD MMMM YYYY') }}</a>
            </div>
        </div>
    </div>
</section>
