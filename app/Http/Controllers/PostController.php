<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }
    
    public function index(User $user) 
    {
        
       $posts = Post::where('user_id', $user->id)->latest()->paginate(8);

       return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }
   
    public function create()
    {
       return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'titulo' => 'required|max:50',
            'descripcion' => 'required',
            'imagen' => 'required',
        ]);

        //Una forma de gusrdar un post
        /*
        Post::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id,
        ]);
        */

        //Otra forma de guardar un Post
        /*
        $post = new Post;
        $post->titulo = $request->titulo;
        $post->descripcion = $request->descripcion;
        $post->imagen = $request->imagen;
        $post->user_id = auth()->user()->id;
        $post->save();
        */

        //Otra forma de guardar un post (despues de relacionar las tablas)
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        //Eliminar la imagen
        $imagenPath = public_path('/uploads/'. $post->imagen);
        if(File::exists($imagenPath)){
            unlink($imagenPath);
            
        }
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
