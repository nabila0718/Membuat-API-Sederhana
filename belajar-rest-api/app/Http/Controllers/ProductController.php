<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //panggil validator untuk memvalidasi inputan
use App\Models\Product; //panggil model Product.php

class ProductController extends Controller
{
    // menambahkan data ke database
    public function store (Request $request) {
        //menvalidasi inputan
        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,cigarette,make-up,cigarette',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);
        //kondisi apabila inputan yang diinginkan tidak sesuai
        if($validator->fails()) {
            //response json akan dikirim jika ada inputan yang salah
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payLoad = $validator->validated();
        //masukkan inputan yang benar ke database (table product)
        Product::create([
            'product_name' => $payLoad['product_name'],
            'product_type' => $payLoad['product_type'],
            'product_price' => $payLoad['product_price'],
            'expired_at' => $payLoad['expired_at']
        ]);
        //response json akan dikirim jika inputan benar
        return response()->json([
            'msg' => 'Data produk berhasil disimpan'
        ],201);
    }

    function showAll(){
        //panggil semua data produk dari tabel product
        $products = Product::all();

        //kirim response json
        return response()->json([
            'msg' => 'Data produk keseluruhan',
            'data' => $products
        ],200);

    }

    function showById($id){

        //mencari data berdasarkan ID produk
        $products = Product::where('id',$id)->first();

        //kondisi apabila data dengan ID ada
        if($products) {

            //respon ketika data ada
            return response()->json([
                'msg' => 'Data produk dengan ID: '.$id,
                'data' => $products
            ],200);
        }
        //response ketika data tidak ada
        return response()->json([
            'msg' => 'Data produk dengan ID: '.$id.' tidak ditemukan',
        ],404);

    }

    function showByName($product_name){
        //cari data berdasarkan produk yang mirip
        $product = Product::where('product_name','LIKE','%'.$product_name.'%')->get();

        //apabila daa produk ada
        if($product->count() > 0) {

            return response()->json([
                'msg'=> 'Data produk dengan nama yang mirip: '.$product_name,
                'data' => $product
            ],200);
        }

        //response ketika data tidak ada
        return response()->json([
            'msg' => 'Data produk dengan nama mirip: '.$product_name.' tidak diterima',
        ],404);

    }

    public function update(Request $request,$id) {
        //memvalidasi inputan
        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,cigarette,make-up,cigarette',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);

        if($validator->fails()) {
            //response json akan dikirim jika ada inputan yang salah
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payLoad = $validator->validated();
        //sunting inputan yang benar kedatabase (table product)
        Product::where('id',$id)->update([
            'product_name' => $payLoad['product_name'],
            'product_type' => $payLoad['product_type'],
            'product_price' => $payLoad['product_price'],
            'expired_at' => $payLoad['expired_at']
        ]);

        //response json akan dikirim jika inputan benar
        return response()->json([
            'msg' => 'Data produk berhasil diubah'
        ],201);
    }
    public function delete($id) {

        $product = Product::where('id',$id)->get();

        if($product) {

            Product::where('id',$id)->delete();

        //response json akan dikirim
        return response()->json([
            'msg' => 'Data produk dengan ID: '.$id.' berhasil dihapus'
        ],200);
    }
    //response json akan jika ID tidak ada
    return response()->json([
        'msg' => 'Data produk dengan ID: '.$id.' tidak ditemukan'
    ],404);
}
}
