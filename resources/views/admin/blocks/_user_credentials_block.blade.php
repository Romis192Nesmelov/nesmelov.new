<p>
    Имя: <b>{{ $user->name }}</b><br>
    E-mail: <b>@include('admin.blocks._email_href_block',['email' => $user->email])</b><br>
    Телефон: <b>@include('admin.blocks._phone_href_block',['phone' => $user->phone])</b><br>
</p>