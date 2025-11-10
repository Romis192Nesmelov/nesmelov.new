@include('blocks._modal_block',['id' => 'message', 'message' => (Session::has('message') ? Session::get('message') : '')])
@if (Session::has('message'))
    <script>$('#message').modal('show');</script>
    <?php Session::forget('message'); ?>
@endif