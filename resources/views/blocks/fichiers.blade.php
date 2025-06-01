<div class="container mb-5">
    <div class="row">
        @foreach($data['fichiers'] ?? [] as $fichier)
            <div class="col-lg-2">
                @if (!empty($fichier['fichier']))

                    <img src="{{ asset($fichier['fichier']) }}" alt="{{ e($fichier['alt'] ?? '') }}" class="img-fluid rounded h-auto"/>

                @endif
                <p class="text-center mt-1">
                    @if (!empty($fichier['lien']) || !empty($fichier['fiche']))
                        <a href="{{ !empty($fichier['fiche']) ? route('fiche.show', $fichier['fiche']) : e($fichier['lien']) }}">
                    @endif
                        <em>{{ e($fichier['titre']) }}</em>
                    @if (!empty($fichier['lien']))
                        </a>
                    @endif

                    @if(!empty($fichier['description']))
                        <br>
                        {{ e($fichier['description']) }}
                    @endif
                </p>
            </div>
        @endforeach
    </div>
</div>
