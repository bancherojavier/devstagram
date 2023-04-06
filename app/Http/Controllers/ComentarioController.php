<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function store(Request $request, User $user, Post $post)
    {
        
        //Validar
        $this->validate($request, [
            'comentario' => 'required|Max:255'
        ]);

        //Alamcenar
        Comentario::create([
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comentario' => $request->comentario
        ]);

        //Mensaje
        return back()->with('mensaje', 'Comentario realizado correctamente');
    }
}
