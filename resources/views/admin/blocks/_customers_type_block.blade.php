@include('admin.blocks._extended_status_block',[
    'status' => $type,
    'descriptions' => ['Премиум','Обычный','Не важный','Проблемный','Под санкциями']
])