<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT; //panggil library JWT
use Illuminate\Support\Facades\Validator; //panggil validator untuk memvalidasi inputan
use Illuminate\Support\Facades\Auth; //panggil fungsi auth (ini otomatis menggunakan tabel user untuk memeriksa)
use Carbon\Carbon;

class UserController extends Controller
{

    //melakukan login dan mendapatkan token JWT
    function login(Request $request){
        //periksa inputan
        $validator = Validator($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //kondisi jika inputan tidak memenuhi kriteria
        if($validator->fails()) {
            return response()->json($validator->messages(),422);
        }

        $validated = $validator->validated();

        //cek email dan password ke tabel user
        if(Auth::attempt($validated)) {

            //isi dari token
            $payload = [
                'name' => 'Administrator',
                'role' => 'admin',
                'iat' => Carbon::now()->timestamp, //waktu token dibuat
                'exp' => Carbon::now()->timestamp +60*60*2, // batas waktu token aktif (disini di set 2 jam)
            ];

            //generate token JWT
            $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

            //response token dikirim
            return response()->json([
                'msg' => 'token berhasil dibuat',
                'data' => 'Bearer '.$token
            ],200);

        } else {

            //response salah
            return response()->json([
                'msg' => 'Email atau password salah'
            ],422);
        }
    }
}
