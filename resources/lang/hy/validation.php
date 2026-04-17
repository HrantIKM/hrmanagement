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

    'accepted' => ':attribute դաշտը պետք է ընդունված լինի։',
    'accepted_if' => ':attribute դաշտը պետք է ընդունված լինի, երբ :other-ը :value է։',
    'active_url' => ':attribute վավեր URL չէ։',
    'after' => ':attribute-ը պետք է :date-ից հետո ամսաթիվ լինի։',
    'after_or_equal' => ':attribute-ը պետք է :date-ից հետո կամ հավասար ամսաթիվ լինի։',
    'alpha' => ':attribute-ը պետք է պարունակի միայն տառեր։',
    'alpha_dash' => ':attribute-ը պետք է պարունակի միայն տառեր, թվեր, գծիկներ և ընդգծումներ։',
    'alpha_num' => ':attribute-ը պետք է պարունակի միայն տառեր և թվեր։',
    'array' => ':attribute-ը պետք է զանգված լինի։',
    'before' => ':attribute-ը պետք է :date-ից առաջ ամսաթիվ լինի։',
    'before_or_equal' => ':attribute-ը պետք է :date-ից առաջ կամ հավասար ամսաթիվ լինի։',
    'between' => [
        'numeric' => ':attribute-ը պետք է :min-ից :max միջակայքում լինի։',
        'file' => ':attribute-ի չափը պետք է :min-ից :max կիլոբայթ միջակայքում լինի։',
        'string' => ':attribute-ի երկարությունը պետք է :min-ից :max նիշ միջակայքում լինի։',
        'array' => ':attribute-ում պետք է :min-ից :max տարր լինի։',
    ],
    'boolean' => ':attribute դաշտը պետք է ճշմարիտ կամ կեղծ լինի։',
    'confirmed' => ':attribute-ի հաստատումը չի համընկնում։',
    'current_password' => 'Գաղտնաբառը սխալ է։',
    'date' => ':attribute-ը վավեր ամսաթիվ չէ։',
    'date_equals' => ':attribute-ը պետք է հավասար լինի :date ամսաթվին։',
    'date_format' => ':attribute-ը չի համապատասխանում :format ձևաչափին։',
    'different' => ':attribute-ը և :other-ը պետք է տարբեր լինեն։',
    'digits' => ':attribute-ը պետք է :digits նիշանոց թվային լինի։',
    'digits_between' => ':attribute-ը պետք է :min-ից :max նիշ միջակայքում լինի։',
    'dimensions' => ':attribute-ի պատկերի չափերը անվավեր են։',
    'distinct' => ':attribute դաշտը կրկնվող արժեք ունի։',
    'email' => ':attribute-ը պետք է վավեր էլ․ փոստի հասցե լինի։',
    'ends_with' => ':attribute-ը պետք է ավարտվի հետևյալներից մեկով՝ :values։',
    'exists' => 'Ընտրված :attribute-ը անվավեր է։',
    'file' => ':attribute-ը պետք է ֆայլ լինի։',
    'filled' => ':attribute դաշտը պետք է արժեք ունենա։',
    'gt' => [
        'numeric' => ':attribute-ը պետք է մեծ լինի :value-ից։',
        'file' => ':attribute-ի չափը պետք է մեծ լինի :value կիլոբայթից։',
        'string' => ':attribute-ի երկարությունը պետք է մեծ լինի :value նիշից։',
        'array' => ':attribute-ում պետք է ավելի քան :value տարր լինի։',
    ],
    'gte' => [
        'numeric' => ':attribute-ը պետք է մեծ կամ հավասար լինի :value-ին։',
        'file' => ':attribute-ի չափը պետք է մեծ կամ հավասար լինի :value կիլոբայթին։',
        'string' => ':attribute-ի երկարությունը պետք է մեծ կամ հավասար լինի :value նիշին։',
        'array' => ':attribute-ում պետք է առնվազն :value տարր լինի։',
    ],
    'image' => ':attribute-ը պետք է պատկեր լինի։',
    'in' => 'Ընտրված :attribute-ը անվավեր է։',
    'in_array' => ':attribute դաշտը գոյություն չունի :other-ում։',
    'integer' => ':attribute-ը պետք է ամբողջ թիվ լինի։',
    'ip' => ':attribute-ը պետք է վավեր IP հասցե լինի։',
    'ipv4' => ':attribute-ը պետք է վավեր IPv4 հասցե լինի։',
    'ipv6' => ':attribute-ը պետք է վավեր IPv6 հասցե լինի։',
    'json' => ':attribute-ը պետք է վավեր JSON տող լինի։',
    'lt' => [
        'numeric' => ':attribute-ը պետք է փոքր լինի :value-ից։',
        'file' => ':attribute-ի չափը պետք է փոքր լինի :value կիլոբայթից։',
        'string' => ':attribute-ի երկարությունը պետք է փոքր լինի :value նիշից։',
        'array' => ':attribute-ում պետք է ավելի քիչ քան :value տարր լինի։',
    ],
    'lte' => [
        'numeric' => ':attribute-ը պետք է փոքր կամ հավասար լինի :value-ին։',
        'file' => ':attribute-ի չափը պետք է փոքր կամ հավասար լինի :value կիլոբայթին։',
        'string' => ':attribute-ի երկարությունը պետք է փոքր կամ հավասար լինի :value նիշին։',
        'array' => ':attribute-ում չպետք է ավելի քան :value տարր լինի։',
    ],
    'max' => [
        'numeric' => 'Դաշտը չպետք է մեծ լինի :max-ից։',
        'file' => ':attribute-ի չափը չպետք է մեծ լինի :max կիլոբայթից։',
        'string' => 'Դաշտը չպետք է երկար լինի :max նիշից։',
        'array' => ':attribute-ում չպետք է ավելի քան :max տարր լինի։',
    ],
    'mimes' => ':attribute-ը պետք է հետևյալ տեսակի ֆայլ լինի՝ :values։',
    'mimetypes' => ':attribute-ը պետք է հետևյալ տեսակի ֆայլ լինի՝ :values։',
    'min' => [
        'numeric' => ':attribute-ը պետք է առնվազն :min լինի։',
        'file' => ':attribute-ի չափը պետք է առնվազն :min կիլոբայթ լինի։',
        'string' => ':attribute-ը պետք է առնվազն :min նիշ լինի։',
        'array' => ':attribute-ում պետք է առնվազն :min տարր լինի։',
    ],
    'multiple_of' => ':attribute-ը պետք է :value-ի բազմապատիկ լինի։',
    'not_in' => 'Ընտրված :attribute-ը անվավեր է։',
    'not_regex' => ':attribute-ի ձևաչափը անվավեր է։',
    'numeric' => ':attribute-ը պետք է թիվ լինի։',
    'password' => 'Գաղտնաբառը սխալ է։',
    'present' => ':attribute դաշտը պետք է ներկայացված լինի։',
    'regex' => ':attribute-ի ձևաչափը անվավեր է։',
    'required' => 'Դաշտը պարտադիր է։',
    'required_if' => ':attribute դաշտը պարտադիր է, երբ :other-ը :value է։',
    'required_unless' => ':attribute դաշտը պարտադիր է, եթե :other-ը :values-ում չէ։',
    'required_with' => ':attribute դաշտը պարտադիր է, երբ :values-ը ներկա է։',
    'required_with_all' => ':attribute դաշտը պարտադիր է, երբ :values-ը ներկա են։',
    'required_without' => ':attribute դաշտը պարտադիր է, երբ :values-ը բացակայում է։',
    'required_without_all' => ':attribute դաշտը պարտադիր է, երբ :values-ից ոչ մեկը ներկա չէ։',
    'prohibited' => ':attribute դաշտը արգելված է։',
    'prohibited_if' => ':attribute դաշտը արգելված է, երբ :other-ը :value է։',
    'prohibited_unless' => ':attribute դաշտը արգելված է, եթե :other-ը :values-ում չէ։',
    'prohibits' => ':attribute դաշտը արգելում է :other-ի ներկայությունը։',
    'same' => ':attribute-ը և :other-ը պետք է համընկնեն։',
    'size' => [
        'numeric' => ':attribute-ը պետք է :size լինի։',
        'file' => ':attribute-ի չափը պետք է :size կիլոբայթ լինի։',
        'string' => ':attribute-ը պետք է :size նիշ լինի։',
        'array' => ':attribute-ում պետք է :size տարր լինի։',
    ],
    'starts_with' => ':attribute-ը պետք է սկսվի հետևյալներից մեկով՝ :values։',
    'string' => 'Դաշտը պետք է տեքստային լինի։',
    'timezone' => ':attribute-ը պետք է վավեր ժամային գոտի լինի։',
    'unique' => 'Այս արժեքն արդեն օգտագործված է։',
    'uploaded' => ':attribute-ի վերբեռնումը ձախողվեց։',
    'url' => 'Դաշտը պետք է վավեր URL լինի։',
    'uuid' => ':attribute-ը պետք է վավեր UUID լինի։',
    'invalid' => 'Դաշտը անվավեր է։',

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

        'after_or_equal' => 'Դաշտը պետք է :date-ից հետո կամ հավասար ամսաթիվ լինի։',
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

    'attributes' => [],
];
