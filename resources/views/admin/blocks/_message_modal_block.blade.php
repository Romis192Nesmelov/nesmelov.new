@include('blocks._modal_block',['id' => 'message', 'message' => (session()->has('message') ? session()->get('message') : '')])
@if (session()->has('message'))
    <script>$('#message').modal('show');</script>
    <?php session()->forget('message'); ?>
@endif
