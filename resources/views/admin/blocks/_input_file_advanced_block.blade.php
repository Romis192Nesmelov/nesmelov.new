<div class="edit-image-preview file-advanced {{ isset($addClass) ? $addClass : '' }}">
    <img src="" />
    @include('admin.blocks._input_file_block', ['label' => '', 'name' =>  isset($name) && $name ? $name : 'image'])
</div>