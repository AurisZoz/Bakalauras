<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthManager extends Controller
{
    function login() 
    {
        return view('auth.login');
    }

    function registration() 
    {
        return view('auth.registration');
    }

    
    function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('main'));
        }

        return redirect(route('login'))->with("error", "Neteisingi prisijungimo duomenys, bandykite dar kartą.");
    }

    function registrationPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-ZÀ-ž\s]+$/',
            'surname' => 'required|regex:/^[a-zA-ZÀ-ž\s]+$/',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required',
        ], [
            'name.required' => 'Vardas yra privalomas',
            'name.regex' => 'Vardas turi būti sudarytas tik iš raidžių',
            'surname.required' => 'Pavardė yra privaloma',
            'surname.regex' => 'Pavardė turi būti sudaryta tik iš raidžių',
            'email.required' => 'El. paštas yra privalomas',
            'email.email' => 'El. paštas turi būti galiojantis',
            'email.unique' => 'El. paštas jau egzistuoja',
            'phone.required' => 'Telefono numeris yra privalomas',
            'phone.numeric' => 'Telefono numeris turi būti sudarytas iš skaičių',
            'phone.unique' => 'Telefono numeris jau egzistuoja',
            'password.required' => 'Slaptažodis yra privalomas',
        ]);

        if ($validator->fails()) {
            return redirect(route('registration'))
                   ->withErrors($validator)
                   ->withInput();
        }

        $data['name'] = $request->name;
        $data['surname'] = $request->surname;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        if (!$user) {
            return redirect(route('auth.registration'))->with("error", "Blogai įvesti duomenys");
        }

        return redirect(route('login'))->with("success", "Naudotojas sėkmingai priregistruotas prie sistemos, prašome prisijungti.");
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}