<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
    $posts = Post::with('user')->latest()->get();          // For You feed
    $explorePosts = Post::with('user')->inRandomOrder()->get(); // Explore feed
    return view('web', compact('posts', 'explorePosts'));
}
}