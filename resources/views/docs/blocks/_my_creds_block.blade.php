<p style="width: 50%;">
    <b>Получатель:</b> {{ $taxType ? 'ИП ' : '' }}Несмелов Роман Сергеевич<br>
    <b>ИНН:</b> {{ getRequisites()['tin'] }}<br>
    @if ($taxType)
        <b>Руководитель:</b> Несмелов Роман Сергеевич<br>
    @endif
    <b>Банк получателя:</b> {{ $taxType ? getRequisites()['bank_ie'] : getRequisites()['bank_se'] }}<br>
    <b>БИК:</b> {{ $taxType ? getRequisites()['bank_id_ie'] : getRequisites()['bank_id_se'] }}<br>
    <b>Счет №:</b> {{ $taxType ? getRequisites()['checking_account_ie'] : getRequisites()['checking_account_se'] }}<br>
    <b>Кор. счет №:</b> {{ $taxType ? getRequisites()['correspondent_account_ie'] : getRequisites()['correspondent_account_se'] }}
</p>
