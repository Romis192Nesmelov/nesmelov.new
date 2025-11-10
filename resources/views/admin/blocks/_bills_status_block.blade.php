@include('admin.blocks._extended_status_block',[
    'status' => $bill->signing,
    'descriptions' => ['Подписан с одной стороны','На подписании второй стороны','Подписан обеими сторонами']
])