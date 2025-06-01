<h2 class="text-xl font-bold mb-4 text-center">
    {{ \App\Enums\TitrePortfolio::tryFrom($data['titre_portfolio'])?->getLabel() ?? e($data['titre_portfolio']) }}
</h2>
