<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\post;
use Illuminate\Http\Request;
use App\Http\Requests\postRequest;
use App\Http\Resources\postResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;

class PostController extends Controller
{
    use ApiResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
//===========================================================================================================================

    public function index()
    {
        $posts = postResource::collection(post::all());
        return $this->Response($posts,"posts all 'index' successfully",200);
    }
//===========================================================================================================================

    public function store(postRequest $request)
    {
        $request->validated();

        $user_name = Auth()->user()->name;

        $post = post::create([
            'title'=>$request->title,
            'category_name'=>$request->category_name,
            'user_name'=>$user_name,
            'body'=>$request->body,
        ]);
        return $this->Response( new postResource($post),'post created successfully', 201);

    }
//===========================================================================================================================

    public function personal_posts()
    {
        $name_user = auth()->user()->name;
        $personal_posts = postResource::collection(post::where('user_name','=',$name_user)->get());
        return $this->Response($personal_posts," all your posts successfully",200);
    }
//===========================================================================================================================

    public function update(postRequest $request, $id)
    {
        $request->validated();

        $name_user = auth()->user()->name;

        //جلب بوسات المستخدم الشخصية أولا لتجنب عملية تعديله على بوست يوزر أخر
        $all_personal_posts = post::where('user_name', $name_user)->get();

        //من خلال جلب فقط المنشورات الخاصة , بعدها بروح بدور على البوست المراد تعديله من ضمن مجموعة النتيجة السابقة
        $post = $all_personal_posts->where('id', $id)->first();

        if ($post) {
            $post->title = $request->title;
            $post->category_name = $request->category_name;
            $post->body = $request->body;
            $post->save();

            return $this->Response(new postResource($post), "Your post updated successfully", 201);
        } else {
            return $this->Response(null, "Post not found", 404);
        }
    }

//===========================================================================================================================

    public function destroy($id)
    {
        $post = post::find($id);
        $post->delete();

        return $this->Response(null,"your post deletedd successfully",201);

    }
}
