@if (Session::has('message') || Session::has('status'))
    @include('blocks._modal_block',['id' => 'message', 'message' => (Session::has('message') ? Session::get('message') : Session::get('status'))])
    <script>$('#message').modal('show');</script>
@endif