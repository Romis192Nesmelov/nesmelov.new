<table class="date">
    <tr>
        <td>г.Москва</td>
        <td>{{ $date ? view('docs.blocks._date_block',['date' => $date])->render() : '«____» _________ 20___' }}</td>
    </tr>
</table>