@include('admin.blocks._button_block', ['type' => 'button', 'icon' => 'fa fa-file-excel-o', 'text' => __('Generate CSV'), 'addClass' => 'form-csv pull-right'])
<a download="tasks.csv" class="download-csv">@include('admin.blocks._button_block', ['type' => 'button', 'icon' => 'icon-file-download', 'text' => __('Download CSV'), 'addClass' => 'pull-right'])</a>
