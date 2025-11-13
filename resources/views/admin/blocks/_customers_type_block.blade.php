@include('admin.blocks._extended_status_block',[
    'status' => $type,
    'descriptions' => [__('Premium'),__('The usual'),__('Not important'),__('Problematic'),__('Under sanctions')]
])
