<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute должен быть приемлемым.',
    'active_url'           => ':attribute не является ссылкой.',
    'after'                => ':attribute должно быть по времени после :date.',
    'alpha'                => ':attribute должен содержать только буквы.',
    'alpha_dash'           => ':attribute должен содержать только буквы, цифры и знаки препинания.',
    'alpha_num'            => ':attribute должен содержать только буквы и цифры.',
    'array'                => ':attribute должен содержать только массивом.',
    'before'               => ':attribute должно быть по времени до :date.',
    'between'              => [
        'numeric' => 'Значение :attribute быть больше :min и меньше :max.',
        'file'    => 'Файл :attribute должен иметь размер больше :min и меньше :max килобайт.',
        'string'  => 'Строка :attribute должна содержать количество символов больше :min и меньше :max.',
        'array'   => 'Массив :attribute должен содержать количество элементов больше :min и меньше :max.',
    ],
    'boolean'              => ':attribute поле должно принимать значения true или false.',
    'confirmed'            => 'Поле :attribute и его подтверждение не совпадают.',
    'date'                 => 'Значение :attribute не является датой.',
    'date_format'          => 'Значение :attribute не соответствует формату :format.',
    'different'            => 'Значение :attribute и значение :other должны быть различны.',
    'digits'               => 'Значение :attribute должно быть :digits цифрами.',
    'digits_between'       => 'Значение :attribute должно быть меньше :min и больше :max.',
    'distinct'             => ':attribute имеет повторяющееся значение.',
    'email'                => ':attribute не является E-mail адресом',
    'exists'               => 'Выбранное значение :attribute не верно.',
    'filled'               => 'Заполнение поля :attribute является обязательным.',
    'image'                => ':attribute должен быть картинкой.',
    'in'                   => 'Выбранное значение :attribute не является верным.',
    'in_array'             => 'Значение поля :attribute не найдено в :other.',
    'integer'              => 'Значение поля :attribute должно быть цифрой.',
    'ip'                   => 'Значение поля :attribute должно быть IP адресом.',
    'json'                 => 'Значение поля :attribute должно быть JSON-строкой.',
    'max'                  => [
        'numeric' => 'Значение поля :attribute не может быть больше :max.',
        'file'    => 'Размер файла :attribute не должен превышать :max килобайт.',
        'string'  => 'Строка :attribute не должна превышать :max символов.',
        'array'   => 'Массив :attribute не должен содержать больше :max элементов.',
    ],
    'mimes'                => 'Файл :attribute должен быть одним из следующих типов: :values.',
    'min'                  => [
        'numeric' => 'Значение поля :attribute должно быть не менее :min.',
        'file'    => 'Размер файла :attribute должен быть не менее :min килобайт.',
//        'string'  => 'Строка :attribute должна состоять не менее чем из :min символов.',
        'string'  => 'Поле :attribute должно быть заполненно!',
        'array'   => 'Массив :attribute должен содержать не менее чем :min элементов.',
    ],
    'not_in'               => 'Выбранное значение :attribute не является верным.',
    'numeric'              => 'Значение :attribute должно быть цифрой.',
    'present'              => 'Значение :attribute должно иметь место быть.',
    'regex'                => 'Значение :attribute не соответствует формату.',
    'required'             => 'Поле :attribute является обязательным.',
    'required_if'          => 'Заполнение поля :attribute требуется в том случае, если :other является :value.',
    'required_unless'      => 'Заполнение поля :attribute требуется в том случае, если :other принимает одно из значений :values.',
    'required_with'        => 'Заполнение поля :attribute требуется в том случае, если присутствует :values.',
    'required_with_all'    => 'Заполнение поля :attribute требуется в том случае, если присутствует одно из значений :values.',
    'required_without'     => 'Заполнение поля :attribute требуется в том случае, если значение :values не присутствует.',
    'required_without_all' => 'Заполнение поля :attribute требуется в том случае, если ни одно из значений :values не присутствует.',
    'same'                 => 'Значение :attribute и значение :other должны совпадать.',
    'size'                 => [
        'numeric' => 'Значение :attribute должно быть :size.',
        'file'    => 'Размер файла :attribute должен быть :size килобайт.',
        'string'  => 'Строка :attribute должна содержать :size символов.',
        'array'   => 'Массив :attribute должен содержать :size элементов.',
    ],
    'string'               => 'Значение поля :attribute должно быть строкой.',
    'timezone'             => 'Значение поля :attribute должно быть временной зоной.',
    'unique'               => 'Такой :attribute уже уже используется.',
    'url'                  => 'Значние :attribute не верного формата.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
