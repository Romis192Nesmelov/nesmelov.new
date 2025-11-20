<table class="names">
    <tr>
        <td class="head">От заказчика</td>
        <td></td>
        <td class="head">От исполнителя</td>
    </tr>
    <tr>
        <td>{{ $director }}</td>
        <td></td>
        <td>
            @if (isset($signature) && $signature)
                <img id="stamp" src="{{ asset('storage/images/'.$signature) }}" />
            @endif
            Несмелов Р.С.
        </td>
    </tr>
</table>
