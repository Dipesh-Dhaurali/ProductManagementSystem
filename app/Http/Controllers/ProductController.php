<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class ProductController extends Controller
{


    public function index(){
        //This method will show product page
        $products=Product::orderBy('created_at','DESC')->get();
        
        return view('products.list',[
            'products'=> $products  
        ]);
    }




    public function create(){
        //This method will show create product page
        return view('products.create');
    }




    public function store(Request $request){
        //This method will store product in db
        $rules=[
            'name' => 'required|min:3',
            'sku' => 'required|min:3',
            'price' => 'required|numeric',
        ];
        if ($request->image!=""){
            $rules['image']='image';
        }
        $validator=Validator::make($request->all(),$rules);

        if ($validator->fails()){
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }
        // insert data in DB
        $product=new Product();
        $product->name=$request->name;
        $product->sku=$request->sku;
        $product->price=$request->price;
        $product->description=$request->description;
        $product->save();
        
        // here we will store image
        if ($request->image!=""){     
                $image=$request->image;
                $ext = $image->getClientOriginalExtension();
                $imageName=time().'.'.$ext; //Unique image name
                
                //save image to product directry
                $image->move(public_path('uploads/products'),$imageName);

                //save image name in database
                $product->image=$imageName;
                $product->save();
                }
        return redirect()->route('products.index')->with('success','product added sucessfully.');
    }




    public function edit($id){
        $product=Product::findOrFail($id);
        return view('products.edit',[
            'product'=>$product
        ]);

    }




    public function update($id,Request $request){
        
        $product=Product::findOrFail($id);

        $rules=[
                'name' => 'required|min:3',
                'sku' => 'required|min:3',
                'price' => 'required|numeric',
            ];
        if ($request->image!=""){
            $rules['image']='image';
        }
        $validator=Validator::make($request->all(),$rules);

        if ($validator->fails()){
            return redirect()->route('products.edit',$product->id)->withInput()->withErrors($validator);
        }
        #update
        $product->name=$request->name;
        $product->sku=$request->sku;
        $product->price=$request->price;
        $product->description=$request->description;
        $product->save();
        
        // here we will store image
        if ($request->image!=""){
            
                //delete old image
                File::delete(public_path('uploads/products/'.$product->image));

                $image=$request->image;
                $ext = $image->getClientOriginalExtension();
                $imageName=time().'.'.$ext; //Unique image name
                
                //save image to product directry
                $image->move(public_path('uploads/products'),$imageName);

                //save image name in database
                $product->image=$imageName;
                $product->save();
                }
        return redirect()->route('products.index')->with('success','product updated sucessfully.');
        
    }



    public function destroy($id){
        $product=Product::findOrFail($id);

        File::delete(public_path('uploads/products/'.$product->image)); //delete image
        //delete product
        $product->delete();
        return redirect()->route('products.index')->with('success','product deleted sucessfully.');
    }




}
