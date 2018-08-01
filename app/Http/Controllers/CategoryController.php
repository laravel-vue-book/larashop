<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Gate;


class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware(function($request, $next){
            
            if(Gate::allows('manage-categories')) return $next($request);

            abort(403, 'Anda tidak memiliki cukup hak akses');
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = \App\Category::paginate(10);

        $filterKeyword = $request->get('name');

        if($filterKeyword){
            $categories = \App\Category::where("name", "LIKE", "%$filterKeyword%")->paginate(10);
        }

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            "name" => "required|min:3|max:20",
            "image" => "required"
        ])->validate();

        $name = $request->get('name');

        $new_category = new \App\Category;
        $new_category->name = $name;
      
        if($request->file('image')){
      
            $image_path = $request->file('image')
                    ->store('category_images', 'public');
            
            $new_category->image = $image_path;
        }

        $new_category->created_by = \Auth::user()->id;
        
        $new_category->slug = str_slug($name, '-');

        $new_category->save();

        return redirect()->route('categories.create')->with('status', 'Category successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = \App\Category::findOrFail($id);

        return view('categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category_to_edit = \App\Category::findOrFail($id);

        return view('categories.edit', ['category' => $category_to_edit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update (Request $request, $id){
        $category = \App\Category::findOrFail($id);

        \Validator::make($request->all(), [
          "name" => "required|min:3|max:20",
          "image" => "required",
          "slug" => [
            "required",
            Rule::unique("categories")->ignore($category->slug, "slug")
          ]
        ])->validate();

        $name = $request->get('name');
        $slug = $request->get('slug');
    
        $category->name = $name;
        $category->slug = $slug;
        
    
        if($request->file('image')){
            if($category->image && file_exists(storage_path('app/public/' . $category->image))){
                \Storage::delete('public/' . $category->name);
            }
    
            $new_image = $request->file('image')->store('category_images', 'public');
    
            $category->image = $new_image;
        }

        $category->updated_by = \Auth::user()->id;

        $category->slug = str_slug($name);
    
        $category->save();

        return redirect()->route('categories.edit', ['id' => $id])->with('status', 'Category successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = \App\Category::findOrFail($id);

        $category->delete();
      
        return redirect()->route('categories.index')
        ->with('status', 'Category successfully moved to trash');
    }


    public function trash(){
        $deleted_category = \App\Category::onlyTrashed()->paginate(10);

        return view('categories.trash', ['categories' => $deleted_category]);
    }

    public function restore($id){
        $category = \App\Category::withTrashed()->findOrFail($id);

        if($category->trashed()){
          $category->restore();
        } else {
          return redirect()->route('categories.index')
          ->with('status', 'Category is not in trash');
        }
      
        return redirect()->route('categories.index')
        ->with('status', 'Category successfully restored');
    }

    public function deletePermanent($id){
        $category = \App\Category::withTrashed()->findOrFail($id);

        if(!$category->trashed()){
          return redirect()->route('categories.index')
          ->with('status', 'Can not delete permanent active category');
        } else {
          $category->forceDelete();
          
          return redirect()->route('categories.index')
          ->with('status', 'Category permanently deleted');
        }
    }


    public function ajaxSearch(Request $request){
        $keyword = $request->get('q');

        $categories = \App\Category::where("name", "LIKE", "%$keyword%")->get();

        return $categories;
    }
}
