<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT; //Panggil library JWT
use Firebase\JWT\key; //panggil library tambahan dari JWT

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //ambil token dan masukan ke variabel $token
        $jwt = $request->bearerToken();
        //cek jika jwt null atau kosong
        if($jwt == 'null' || $jwt ==''){
            //jika ya maka response ini muncul
            return response()->json([
                'msg' => 'Akses ditolak, token tidak memenuhi'
            ],401);
        } else {

            //decode token
            $jwtDecoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            //JIKA TOKEN ITU MILIK ADMIN
            if($jwtDecoded->role == 'admin'){
                return $next($request);
            }

            //jika tidak maka response ini muncul
            return response()->json([
                'msg' => 'Akses ditolak, token tidak memenuhi'
            ],401);
        }
    }
}
