<p style="width: 50%;">
    <b>Плательщик:</b>
    @if (!$customer->ltd)
        ИП
    @elseif ($customer->ltd == 1)
        ООО
    @else
        ЗАО
    @endif
    «{{ $customer->name }}»<br>
    <b>Генеральный директор:</b> {{ $customer->director }}<br>

    @foreach(['ОГРН' => 'ogrn', 'ОКПО' => 'okpo', 'ОКВЭД' => 'okved', 'ОКТМО' => 'oktmo', 'ИНН' => 'inn', 'КПП' => 'kpp'] as $name => $item)
        @if ($customer[$item])
            <b>{{ $name }}:</b> {{ $customer[$item] }}<br>
        @endif
    @endforeach

    <b>Адрес:</b> {{ $customer->address }}<br>

    @if ($customer->bank_id)
        <b>Банк:</b> {{ $customer->bank->name }}<br>
        <b>БИК:</b>  {{ $customer->bank->bank_id }}<br>
    @endif

    <b>Расчетный счет:</b> {{ $customer->payment_account }}<br>
    <b>Корр.счет:</b> {{ $customer->correspondent_account }}
</p>