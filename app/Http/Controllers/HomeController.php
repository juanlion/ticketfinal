<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('soloadmin',['only'=> ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();

        return view('home', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'tipo' => 'required',
        ]);

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->tipo = $request->input('tipo');
            $user->password = Hash::make($request->input('password'));
            $user->telefono = $request->input('telefono');
            $user->direccion = $request->input('direccion');
            $user->save();

        return redirect('/home')->with('status', 'Los datos se guardaron correctamente.');
        
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'tipo' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->update($validatedData);

        return redirect('/users')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {

    }



}
