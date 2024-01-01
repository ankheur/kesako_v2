<section class="doc_banner_area banner_creative1">
    <ul class="list-unstyled banner_shap_img">
        <li><img src="{{ asset('build/img/new/banner_shap1.png') }}" alt=""></li>
        <li><img src="{{ asset('build/img/new/banner_shap4.png') }}" alt=""></li>
        <li><img src="{{ asset('build/img/new/banner_shap3.png') }}" alt=""></li>
        <li><img src="{{ asset('build/img/new/banner_shap2.png') }}" alt=""></li>
        <li><img data-parallax='{"x": -180, "y": 80, "rotateY":2000}' src="{{ asset('build/img/new/plus1.png') }}" alt=""></li>
        <li><img data-parallax='{"x": -50, "y": -160, "rotateZ":200}' src="{{ asset('build/img/new/plus2.png') }}" alt=""></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    <div class="container">
        <div class="doc_banner_content">
            <h2 class="wow fadeInUp">Tout ce que vous devez savoir</h2>
            <p class="wow fadeInUp" data-wow-delay="0.2s">Recherchez ici le sujet qui vous intéresse</p>
            <form action="{{ route('recherche') }}" method="POST" class="header_search_form" autocomplete="off" role="search">
                @csrf
                <div class="header_search_form_info">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="icon_search"></i>
                            <input type='search' autocomplete="off" id="searchbox" name="search"
                                   placeholder="Tapez votre recherche" aria-label="Recherchez sur le site" />
                            {{--<div class="header_search_form_panel">
                                <ul class="list-unstyled">
                                    <li>Help Desk
                                        <ul class="list-unstyled search_item">
                                            <li><span>Configuration</span><a href="#">How to edit host and
                                                    port?</a></li>
                                            <li><span>Configuration</span><a href="#">The dev Property</a></li>
                                        </ul>
                                    </li>
                                    <li>Support
                                        <ul class="list-unstyled search_item">
                                            <li><span>Pages</span><a href="#">The asyncData Method</a></li>
                                        </ul>
                                    </li>
                                    <li>Documentation
                                        <ul class="list-unstyled search_item">
                                            <li><span>Getting Started</span><a href="#">The asyncData Method</a>
                                            </li>
                                            <li><span>Getting Started</span><a href="#">The asyncData Method</a>
                                            </li>
                                            <li><span>Getting Started</span><a href="#">The asyncData Method</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>--}}
                        </div>
                    </div>

                </div>
                <div class="header_search_keyword">
                    <span class="header-search-form__keywords-label">Sujets populaires :</span>
                    <ul class="list-unstyled">
                        @foreach($popularPosts as $popularPost)
                            <li class="wow fadeInUp" data-wow-delay="0.2s"><a href="{{ route('article.show', $popularPost->slug) }}">{{ $popularPost->titre }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </form>
        </div>
    </div>
</section>
