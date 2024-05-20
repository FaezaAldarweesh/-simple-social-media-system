<?php

namespace App\Http\Controllers;

use App\Models\post;
use App\Models\comment;
use Illuminate\Http\Request;
use App\Http\Requests\commentRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\commentResource;

class CommentController extends Controller
{
    use ApiResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
//===========================================================================================================================

    public function add(commentRequest $request,$id)
    {
        $request->validated();

        $post = post::find($id);

        $user_name = Auth()->user()->name;

        $comment = comment::create([
            'post_id'=>$post->id,
            'user_name'=>$user_name,
            'comment'=>$request->comment,
        ]);
        return $this->Response( new commentResource($comment),'comment created successfully', 201);

    }
//===========================================================================================================================
    public function update(commentRequest $request, $id)
    {
        $request->validated();

        $name_user = auth()->user()->name;

        $all_personal_comments = comment::where('user_name', $name_user)->get();

        $comment = $all_personal_comments->where('post_id', $id)->first();

        if ($comment) {
            $comment->comment = $request->comment;
            $comment->save();

            return $this->Response(new commentResource($comment), "Your comment updated successfully", 201);
        } else {
            return $this->Response(null, "Comment not found", 404);
        }
    }
//===========================================================================================================================

    public function destroy($id)
    {
        $comment = comment::find($id);
        $comment->delete();

        return $this->Response(null,"your comment deleted successfully",201);

    }
}
