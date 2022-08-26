<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Product_image;
use DataTables;

class ProductController extends Controller
{
    public function index()
    {
     
        $Category= Category::all();
        return view('product', ['Category'=> $Category]);
    }

    public function category_search(Request $request)
    {
        $selectdata     = Category::query();
        if($request->search)
            $selectdata = $selectdata->where('name', 'like', '%' .$request->search. '%');
        $selectdata     = $selectdata->simplePaginate(50);
        $data = [];
        foreach($selectdata as $key => $item){
            $data[$key]['id']           = $item->id;
            $data[$key]['text']         = $item->name;
        }
        $page=true;
        if(empty($selectdata->nextPageUrl())){
            $page=false;
        }
        return ['results'=>$data,'pagination'=>['more'=>$page]];
         
    }

    public function product_sub(Request $request)
    {
        
        $data = [
            'category_id'=>$request->sel_emp,
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price
        ];
        Product::create($data);

        $insert_id = Product::insertGetId($data);
        // dd($insert_id);

        if($files=$request->file('files')){
            foreach($files as $key => $file){
                $name=$file->getClientOriginalName();
                $file->move('image',$name);
                $filedata= new Product_image();
                $filedata->name = $name;
                $filedata->product_id = $insert_id;
                $filedata->orders = $key + 1;
                $filedata->save();
            }
        }
        return response()->json(['success'=>'Ajax Multiple fIle has been uploaded']);
    }


    public function datatable(Request $request)
    {
        $data = Product::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'"  data-action="'.route('product_edit', $row->id).'" data-original-title="Edit" class="edit btn btn-primary btn-sm product_edit">Edit</a>';
   
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-action="'.route('product_delete', $row->id).'" data-original-title="Delete" class="btn btn-danger btn-sm product_delete">Delete</a>';
 
                         return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function product_delete($id)
    {
        $cliente = Product::find($id);
        Product_image::where('product_id', $id)->delete();
        $cliente->delete();
    }

    public function product_get(Request $request)
    {
        $editModeData = Product::find($request->id);
        $editimageData = Product_image::where('product_id', $request->id)->get();
        return response()->json(['product' => $editModeData , 'images' => $editimageData]);
        
            
    }

    public function product_edit(Request $request)
    {
        $id= $request->id;
        $post= Product::find($id);
        $post->category_id = $request->editsel_emp;
        $post->name = $request->editname;
        $post->description = $request->editdescription;
        $post->price = $request->editprize;
        $post->save();
        if (request()->hasFile('files') && request('files') != '') {

            $product = Product_image::where('product_id', $id)->get();
            
            foreach($product as $pro){
                if ( file_exists(public_path('public/image')."/".$pro->name) ) {
                    unlink(public_path('public/image')."/".$pro->name);
                    
                }
            }
                Product_image::where('product_id', $id)->delete();
                        if($files=$request->file('files')) {
                            foreach($files as $key => $file) {
                                $name = $file->getClientOriginalName();
                                $file->move('image',$name);
                                $filedata= new Product_image();
                                $filedata->product_id = $id;
                                $filedata->name = $name;
                                $filedata->orders = $key + 1;
                                $filedata->save();
                                
                            }
                        }
        
        }
    }
}