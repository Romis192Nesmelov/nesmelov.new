@include('admin.blocks._extended_status_block',[
    'status' => $bill->signing,
    'descriptions' => [__('Signed on one side'),__('At the signing of the second party'),__('Signed by both sides')]
])
