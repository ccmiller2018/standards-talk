<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostFormRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()->where('active',1)->orderBy('created_at','desc')->paginate(5);
        $title = 'Latest Posts';
        return view('home')->withPosts($posts)->withTitle($title);
    }

    public function create(Request $request): RedirectResponse|View
    {
        if ($request->user()->can_post()) {
            return view('posts.create');
        }

        return redirect('/')->withErrors('You have not sufficient permissions for writing post');
    }

    public function store(PostFormRequest $request): RedirectResponse
    {
        $post = new Post();
        $post->title = $request->get('title');
        $post->body = $request->get('body');
        $post->slug = Str::slug($post->title);

        $duplicate = Post::where('slug', $post->slug)->first();
        if ($duplicate instanceof Post) {
            return redirect('new-post')->withErrors('Title already exists.')->withInput();
        }

        $post->author_id = $request->user()->id;
        if ($request->has('save')) {
            $post->active = 0;
            $message = 'Post saved successfully';
        } else {
            $post->active = 1;
            $message = 'Post published successfully';
        }
        $post->save();
        return redirect('edit/' . $post->slug)->withMessage($message);
    }

    public function show($slug): RedirectResponse|View
    {
        $post = Post::where('slug', $slug)->first();

        if ($post === false) {
            return redirect('/')->withErrors('requested page not found');
        }

        $comments = $post->comments;

        return view('posts.show')->withPosts($post)->withComments($comments);
    }

    public function edit(Request $request,$slug): RedirectResponse|View
    {
        $post = Post::where('slug',$slug)->first();
        if (!is_null($post) && ($request->user()->id == $post->author_id || $request->user()->is_admin()))
            return view('posts.edit')->with('post',$post);
        return redirect('/')->withErrors('you have not sufficient permissions');
    }

    public function update(Request $request): RedirectResponse
    {
        $post_id = $request->input('post_id');
        $post = Post::query()->where('id', '=', $post_id)->first();
        if (is_null($post) || ($post->author_id != $request->user()->id && !$request->user()->is_admin())) {
            return redirect('/')->withErrors('you have not sufficient permissions');
        }

        $title = $request->input('title');
        $slug = Str::slug($title);
        $duplicate = Post::where('slug', $slug)->first();
        if (!is_null($duplicate)) {
            if ($duplicate->id != $post_id) {
                return redirect('edit/' . $post->slug)->withErrors('Title already exists.')->withInput();
            }

            $post->slug = $slug;
        }

        $post->title = $title;
        $post->body = $request->input('body');

        if ($request->has('save')) {
            $post->active = 0;
            $message = 'Post saved successfully';
            $landing = 'edit/' . $post->slug;
        } else {
            $post->active = 1;
            $message = 'Post updated successfully';
            $landing = $post->slug;
        }
        $post->save();
        return redirect($landing)->withMessage($message);
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        $post = Post::query()->where('id', '=', $id)->first();
        $data = [];

        if (!is_null($post) && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
            $post->delete();
            $data['message'] = 'Post deleted Successfully';
        } else {
            $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
        }

        return redirect('/')->with($data);
    }
}
