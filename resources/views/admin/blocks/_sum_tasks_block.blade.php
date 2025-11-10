<div class="total-line text-grey-300">
    Итого {{ isset($addTotalDesk) ? '('.$addTotalDesk.')' : '' }}: <b>{{ round($sum - (isset($showAverageIncome) && $showAverageIncome ? ( (int)Settings::getSettings()['my_status'] ? (int)Settings::getSettings()['fix_tax'] : 0 ) : 0)) }}₽</b><br>
    @if (Auth::user()->is_admin)
        @if (isset($showAverageIncome) && $showAverageIncome)
            Средний доход в месяц: <b>{{ round( ($sum - ( ((int)Settings::getSettings()['my_status'] || $data['fix_tax']->year == (int)date('Y')) ? $data['fix_tax']->value : 0 ) ) / ($data['year'] == date('Y') ? (int)date('n') : 12) ) }}₽</b><br>
            @if ((int)Settings::getSettings()['my_status'])
                Фикс. ПФРФ: <b>{{ Settings::getSettings()['fix_tax'] }}₽</b><br>
            @endif
        @endif
        Налоги: <b>{{ $duty ? round($duty) : 0 }}₽</b><br>
    @endif
    Сторонний %: <b>{{ round($percents) }}₽</b>
</div>
