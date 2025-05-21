<section class="page_breadcrumb">
    <div class="container custom_container">
        <div class="row">
            <div class="col-sm-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pages.home') }}">Accueil</a></li>
                        {{--<li class="breadcrumb-item"><a href="{{ route('domaine.show', $fiche->domaine) }}">{{ $fiche->domaine->titre }}</a></li>--}}
                        <li class="breadcrumb-item active" aria-current="page">{{ $cat->denomination ?? $cat }}</li>
                    </ol>
                </nav>
            </div>
            @if(isset($cat->published_at))
            <div class="col-sm-5">
                <a href="#" class="date"><i class="icon_clock_alt"></i>Publié le {{ \Carbon\Carbon::parse($cat->published_at)->isoFormat('DD MMMM YYYY') }}</a>
            </div>
            @endif
        </div>
    </div>
</section>
