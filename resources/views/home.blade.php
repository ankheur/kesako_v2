<x-front-layout :domaines="$domaines">
    <section class="doc_features_area">
        {{--<img class="doc_features_shap" src="{{ asset('build/img/new/shap_white.png') }}" alt="">--}}
        <div class="container">
            <div class="doc_features_inner">
                @foreach($domaines as $domaine)
                    <div class="media doc_features_item wow fadeInUp align-items-center" data-wow-delay="0.1s" data-wow-duration="0.5s">
                        <img width="50" height="50" src="{{ Storage::url($domaine->icone) }}" alt="icone domaine {{ $domaine->titre }}">
                        <div class="media-body">
                            <a href="{{ route('domaine.show', $domaine->slug) }}">
                                <h4>{{ $domaine->titre }}</h4>
                            </a>
                            {{--<p>245 Posts</p>--}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="doc_blog_grid_area">
        <div class="container">
            <div class="text-center">
                <h2 class="wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">Dernières fiches</h2>
            </div>
            <div class="row blog_grid_tab">
                @foreach($fiches as $fiche)
                    <livewire:miniature-fiche :$fiche :key="$fiche->id">
                @endforeach
            </div>
        </div>
    </section>
    {{--<section class="doc_subscribe_area mt-5 mb-5">
        <div class="container">
            <div class="doc_subscribe_inner">
                <img class="one" src="{{ asset('build/img/new/subscribe_shap.png') }}" alt="">
                <img class="two" src="{{ asset('build/img/new/subscribe_shap_two.png') }}" alt="">
                <div class="text wow fadeInLeft" data-wow-delay="0.2s">
                    <h2>Rejoignez-nous ! <br>Et participez à transmettre le savoir</h2>
                </div>
                <form action="#" class="doc_subscribe_form wow fadeInRight mailchimp" data-wow-delay="0.4s"
                      method="post">
                    <div class="form-group">
                        <div class="input-fill">
                            <input type="email" name="EMAIL" id="email" class="memail"
                                   placeholder="Votre adresse mail">
                        </div>
                        <button type="submit" class="submit_btn">S'inscrire</button>
                        <p class="mchimp-errmessage" style="display: none;"></p>
                        <p class="mchimp-sucmessage" style="display: none;"></p>
                    </div>
                    --}}{{--<ul class="list-unstyled">
                        <li><a href="#">Messenger</a></li>
                        <li><a href="#">Product Tours</a></li>
                        <li><a href="#">Inbox and more</a></li>
                    </ul>--}}{{--

                </form>
            </div>
        </div>
    </section>--}}
</x-front-layout>

