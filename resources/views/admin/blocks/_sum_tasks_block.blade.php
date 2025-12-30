<div class="total-line text-grey-300">
    {{ __('Total').' '.(isset($addTotalDesk) ? '('.$addTotalDesk.')' : '') }}: <b>{{ round($sum - (isset($showAverageIncome) && $showAverageIncome ? ( (int)getSettings()['my_status'] ? (int)getSettings()['fix_tax'] : 0 ) : 0)) }}₽</b><br>
    @if (auth()->user()->is_admin)
        @if (isset($showAverageIncome) && $showAverageIncome)
            {{ __('Average monthly income:') }} <b>{{ round( ($sum - ( ((int)getSettings()['my_status'] || ($data['fix_tax']->year ?? $data['year']) == (int)date('Y')) ? $data['fix_tax']->value : 0 ) ) / ($data['year'] == date('Y') ? (int)date('n') : 12) ) }}₽</b><br>
            @if ((int)getSettings()['my_status'])
                {{ __('Fix PFRF') }}: <b>{{ getSettings()['fix_tax'] }}₽</b><br>
            @endif
        @endif
        {{ __('Taxes:') }} <b>{{ $duty ? round($duty) : 0 }}₽</b><br>
    @endif
    {{ __('Third-party %') }}: <b>{{ round($percents) }}₽</b>
</div>
