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

    'accepted' => ':attribute qəbul edilməlidir.',
    'active_url' => ':attribute etibarlı bir URL deyil.',
    'after' => ':attribute :date tarixindən sonra bir tarix olmalıdır.',
    'after_or_equal' => ':attribute :date tarixindən sonra vəya bərabər bir tarix olmalıdır.',
    'alpha' => ':attribute yalnız hərflərdən ibarət ola bilər.',
    'alpha_dash' => ':attribute yalnız hərflərdən, rəqəmlərdən, tire və alt xəttlərdən ibarət ola bilər.',
    'alpha_num' => ':attribute yalnız hərf və rəqəmlərdən ibarət ola bilər.',
    'array' => ':attribute bir massiv olmalıdır.',
    'before' => ':attribute tarixi :date tarixindən əvvəl bir tarix olmalıdır.',
    'before_or_equal' => ':attribute tarixi :date tarixindən əvvəl vəya eyni bir tarix olmalıdır.',
    'between' => [
        'numeric' => ':attribute :min və :max aralığında olmalıdır.',
        'file' => ':attribute :min və :max kilobayt aralığında olmalıdır.',
        'string' => ':attribute :min və :max xarakter aralığında olmalıdır.',
        'array' => ':attribute :min və :max ədəd aralığında olmalıdır.',
    ],
    'boolean' => ':attribute true vəya false olmalıdır.',
    'confirmed' => ':attribute təsdiq uyğun gəlmir.',
    'date' => ':attribute etibarlı bir tarix deyil.',
    'date_equals' => ':attribute tarixi :date tarixinə bərabər bir tarix olmalıdır.',
    'date_format' => ':attribute formatı :format formatına uyğun gəlmir.',
    'different' => ':attribute və :other fərqli olmalıdır.',
    'digits' => ':attribute rəqəmi :digits rəqəmdən ibarət olmalıdır.',
    'digits_between' => ':attribute rəqəmi :min və :max aralığında olmalıdır.',
    'dimensions' => ':attribute yanlış görüntü ölçüləri var.',
    'distinct' => ':attribute sahənin dublikat dəyəri var.',
    'email' => ':attribute etibarlı bir e-poçt ünvanı olmalıdır.',
    'ends_with' => ':attribute aşağıdakilərdən biri ilə bitməlidir: :values.',
    'exists' => 'Seçilmiş :attribute etibarsızdır.',
    'file' => ':attribute fayl olmalıdır.',
    'filled' => ':attribute sahənin bir dəyəri olmalıdır.',
    'gt' => [
        'numeric' => ':attribute :value dəyərindən böyük olmalıdır.',
        'file' => ':attribute :value kilobaydan böyük olmalıdır.',
        'string' => ':attribute :value xarakterdən böyük olmalıdır.',
        'array' => ':attribute :value ədəd dəyərdən çox olmalıdır.',
    ],
    'gte' => [
        'numeric' => ':attribute :value ədədindən böyük vəya bərabər olmalıdır.',
        'file' => ':attribute :value kilobaytdan böyük vəya bərabər olmalıdır.',
        'string' => ':attribute :value xarakterdən böyük vəya bərabər olmalıdır.',
        'array' => ':attribute :value ədəd vəya daha çox dəyər olmalıdır.',
    ],
    'image' => ':attribute şəkil olmalıdır.',
    'in' => ':attribute keçərli deyil.',
    'in_array' => ':attribute :other dəyərində yoxdur.',
    'integer' => ':attribute rəqəm olmalıdır.',
    'ip' => ':attribute IP adresi olmalıdır.',
    'ipv4' => ':attribute IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute IPv6 adresi olmalıdır.',
    'json' => ':attribute JSON növündə olmalıdır.',
    'lt' => [
        'numeric' => ':attribute :value ədədindən kiçik olmalıdır.',
        'file' => ':attribute :value kilobaytdan kiçik olmalıdır.',
        'string' => ':attribute :value xarakterdən kiçik olmalıdır.',
        'array' => ':attribute :value dəyərdən kiçik olmalıdır.',
    ],
    'lte' => [
        'numeric' => ':attribute :value ədədindən böyük vəya bərabər olmalıdır.',
        'file' => ':attribute :value kilobaytdan böyük vəya bərabər olmalıdır.',
        'string' => ':attribute :value xarakterdən böyük vəya bərabər olmalıdır.',
        'array' => ':attribute :value ədəd vəya daha çox dəyər olmalıdır.',
    ],
    'max' => [
        'numeric' => ':attribute :max ədədindən kiçik olmalıdır.',
        'file' => ':attribute :max kilobaytdan kiçik olmalıdır.',
        'string' => ':attribute :max xarakterdən kiçik olmalıdır.',
        'array' => ':attribute :max dəyərdən kiçik olmalıdır.',
    ],
    'mimes' => ':attribute aşağıdaki fayl tiplərindən biri olmalıdır: :values.',
    'mimetypes' => ':attribute aşağıdaki fayl tiplərindən biri olmalıdır: :values.',
    'min' => [
        'numeric' => ':attribute ən azı :min olmalıdır.',
        'file' => ':attribute ən azı :min kilobayt olmalıdır.',
        'string' => ':attribute ən azı :min xarakter olmalıdır.',
        'array' => ':attribute ən azı :min dəyərdən ibarət olmalıdır.',
    ],
    'not_in' => 'Seçilmiş :attribute düzgün deyil.',
    'not_regex' => 'Seçilmiş :attribute format növü düzgün deyil.',
    'numeric' => 'Daxil olunmuş :attribute rəqəm olmalıdır.',
    'password' => 'Şifrə yanlışdır.',
    'present' => ':attribute sahəsi mövcud olmalıdır.',
    'regex' => 'Daxil olunmuş :attribute formatı yanlışdır.',
    'required' => ':attribute daxil olunmayıb.',
    'required_if' => ':other :value dəyərinə bərabər olarsa :attribute məlumatını daxil etmək lazımdır.',
    'required_unless' => ':other dəyəri aşağıdaki dəyərlər içərisində olmadığı halda: :values :attribute daxil etmək lazımdır.',
    'required_with' => ':values mövcud olduğu halda :attribute daxil etmək lazımdır.',
    'required_with_all' => ' :values mövcud olduğu halda :attribute daxil etmək lazımdır.',
    'required_without' => ':values mövcud olmadığı halda :attribute daxil etmək lazımdır.',
    'required_without_all' => ':values dəyərlərindən heç biri mövcud olmadıqda :attribute daxil etmək lazımdır.',
    'same' => ':attribute və :other bərabər olmalıdır.',
    'size' => [
        'numeric' => ':attribute :size ölçüdə olmalıdır.',
        'file' => ':attribute :size kilobayt olmalıdır.',
        'string' => ':attribute :size xarakter olmalıdır.',
        'array' => ':attribute daxilində :size qədər dəyər olmalıdır.',
    ],
    'starts_with' => ':attribute aşağıda göstərilən dəyərlərlə başlamamalıdır: :values',
    'string' => ':attribute tekst formasında olmalıdır.',
    'timezone' => ':attribute keçərli vaxt zonası olmalıdır.',
    'unique' => 'Daxil olunmuş :attribute mövcuddur.',
    'uploaded' => ':attribute yüklənə bilmədi.',
    'url' => ':attribute formatı keçərli deyil.',
    'uuid' => ':attribute keçərli UUID-də olmalıdır.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'E-poçt ünvanı',
        'description' => 'Açıqlama',
        'status' => 'Status',
        'task_id' => 'İdentifikasiya nömrəsi',
        'id' => 'İdentifikasiya nömrəsi',
        'fin_code' => 'Fin Kodu',
        'serial_number' => 'Seriya Nömrəsi',
        'phone' => 'Telefon nömrəsi',
        'position' => 'Vəzifə',
        'user_group' => 'İstifadəçi qrupu',
        'name' => 'Adı',
        'surname' => 'Soyadı',
        'address' => 'Ünvan',
        'photo' => 'Qoşma',
        'item_id' => 'Qiymətləndirilmə maddəsi',
        'assignment_id' => 'Təyinat',
        'regions' => 'Regionlar',
        'title' => 'Başlıq',
        'key' => 'Açar adı',
        'body' => 'Məzmun',
        'host' => 'Smtp host',
        'port' => 'Smtp port',
        'password' => 'Şifrə',
        'username' => 'İstifadəçi adı',
        'send_time' => 'Göndəriləcəyi vaxt',
        "encryption" => 'Şifrələmə növü',
        'sender_name' => 'Göndərənin adı',
        'sender_email' => 'Göndərənin e-poçt ünvanı',
        'organization_id' => 'Təşkilat',
        'phone_number' => 'Telefon nömrəsi',
        'field_code' => 'Sahə kodu',
        'measurement_unit' => 'Ölçü vahidi',
        'module_type' => 'Nomenklatura növü',
        'module' => 'Nomenklatura növü',
        'fhn_id' => 'FHN - eyniləşmə kodu',
        'group_id' => 'Qrup',
        'days' => 'Günlər',
        'day' => 'Gün',
        'months' => 'Aylar',
        'month' => 'Ay',
        'years' => 'İllər',
        'year' => 'İl',
        'week' => 'Həftə',
        'weeks' => 'Həftələr',
        'assignment_type' => 'Nomenklatura növü',
        'maximum_delay_days' => 'Maksimum gecikmə günü',
        'renewal_periodic_days' => 'Yenilənmə periodu (gün)',
        'assignment_observation_groups' => 'Müşahidə qrupu',
        'is_global_region' => 'Ümumi regiona aid olub olmaması',
        'assigned_items' => 'Təyinata bağlı nomenklaturalar',
        'risky_handling_type' => 'Müşahidə qrupuna riskli olmasa göndərilsin parameteri',
        'salary_price' => 'Əmək haqqı',
        'salary_date' => 'Əmək haqqı günü'
    ],

];
