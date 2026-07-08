<?php

return [
    'required' => ':attributeを入力してください。',
    'email' => ':attributeの形式が正しくありません。',
    'min' => [
        'string' => ':attributeは:min文字以上で入力してください。',
    ],
    'confirmed' => ':attributeが確認用と一致しません。',
    'unique' => ':attributeは既に登録されています。',

    'password' => [
        'numbers' => ':attributeには数字を含めてください。',
    ],

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],
];