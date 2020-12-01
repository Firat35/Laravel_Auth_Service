<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;


class AuthController extends Controller
{   
    public function register(Request $request) 
    { 
        $request->validate([
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required|min:6',
            'cpassword' => 'required|same:password',
            'addresses' => 'required|array|min:1',
            'addresses.*.country' => 'required',
            'addresses.*.city' => 'required'
        ]);
  
        try{
            $user = User::create([
                'name' => $request->name, 
                'email' => $request->email, 
                'password' => bcrypt($request->password)
            ]);
        } catch(QueryException $e) {
            if($e->errorInfo[1] == 1062) {
                return response()->json(['error'=> 'Email must be unique!'],500); 
            } else {
                throw $e;
            }
        }
    
        $user->save();
        
        for($i = 0; $i< count($request->addresses); $i++ ) 
        {
            Address::create([
                    'user_id' => $user->id,
                    'country' => $request->input('addresses.'.$i.'.country'),
                    'city' => $request->input('addresses.'.$i.'.city')
                ]);
        }
        $success['id'] =  $user->id;
        $success['name'] =  $user->name;
        $success['token'] = $user->createToken('Laravel Password')->accessToken;
        
        return response()->json(['success'=> $success],200);    
    }

    public function login(Request $request)
    { 
        $request->validate([
            'email' => 'required|email|exists:users,email', 
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Token')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } 
        else {
            $response = ["message" =>'User does not exist'];
            return response($response, 404);
        }
        
        
    }
}
