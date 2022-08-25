<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DataTables;



class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return view('category');
    }

    public function category_sub(Request $request)
    {
        // dd($request->all());
        $data = [
        'name' => $request->name,
        ];
        Category::create($data);

    }

    public function category_data()
    {
        $data = Category::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'"  data-action="'.route('category_edit', $row->id).'" data-original-title="Edit" class="edit btn btn-primary btn-sm category_edit">Edit</a>';
   
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-action="'.route('category_delete', $row->id).'" data-original-title="Delete" class="btn btn-danger btn-sm category_delete">Delete</a>';
 
                         return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function category_delete($id)
    {
        $Category = Category::find($id); 
        $Category->delete();
    }

    public function category_get(Request $request)
    {
        $editModeData = Category::find($request->id);

            return $editModeData;
    }

    public function category_edit(Request $request)
    {
        $id= $request->id;
       $post= Category::find($id);
       $post->name = $request->name;
        $post->save();
    }


}
