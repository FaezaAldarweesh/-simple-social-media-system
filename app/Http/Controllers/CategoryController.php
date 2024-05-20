<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\categoryRequest;
use App\Http\Resources\categoryResource;

class CategoryController extends Controller
{
    use ApiResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
//===========================================================================================================================

    public function index()
    {
        $categories = categoryResource::collection(category::all());
        return $this->Response($categories,"categories all 'index' successfully",200);
    }
//===========================================================================================================================

    public function store(categoryRequest $request)
    {
        $request->validated();

        $category = category::create([
            'name' => $request->name,
        ]);
        return $this->Response( new categoryResource($category),'category created successfully', 201);

    }
//===========================================================================================================================

    public function show($id)
    {
        $category = category::find($id);
        return $this->Response(new categoryResource($category),"category show successfully",200);
    }
//===========================================================================================================================

    public function update(categoryRequest $request, $id)
    {
        $request->validated();

        $category = category::find($id);
        $category->name = $request->name;
        $category->save();

        return $this->Response(new categoryResource($category),"category update successfully",201);

    }
//===========================================================================================================================

    public function destroy($id)
    {
        $category = category::find($id);
        $category->delete();

        return $this->Response(null,"category delete successfully",201);

    }
}
