<table class="names">
    <tr>
        @if (isset($director))
            <td class="head">От заказчика</td>
        @endif
        <td></td>
        <td class="head">От исполнителя</td>
    </tr>
    <tr>
        @if (isset($director))
            <td>{{ $director }}</td>
        @endif
        <td></td>
        <td>
            @if (isset($signature) && $signature)
                <img id="stamp" src="{{ asset('storage/images/'.$signature) }}" />
            @endif
            Несмелов Р.С.
        </td>
    </tr>
</table>
