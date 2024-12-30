<?php

return [
    'confirmed' => ':attribute nesutampa.',
    'min' => [
        'string' => ':attribute turi būti bent :min simbolių.',
    ],
    'required' => ':attribute yra privalomas.',
    'custom' => [
        'new_password' => [
            'confirmed' => 'Slaptažodžiai nesutampa.',
        ],
        'title' => [
            'required' => 'Reabilitacijos plano pavadinimas yra privalomas.',
        ],
        'content' => [
            'required' => 'Turinys negali būti tuščias.',
        ],
        'phone' => [
            'regex' => 'Telefono numeris turi būti sudarytas iš skaičių.',
        ],
    ],
    'attributes' => [
        'current_password' => 'Slaptažodis',
        'new_password' => 'Slaptažodis',
        'new_password_confirmation' => 'Naujo slaptažodžio patvirtinimas',
        'title' => 'Reabilitacijos plano pavadinimas',
        'content' => 'Turinys',
        'phone' => 'Telefono numeris',
        'name' => 'Vardas',
        'surname' => 'Pavardė',
        'email' => 'El. Paštas',
    ],
];
