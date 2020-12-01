<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Address;

class UserController extends Controller
{   
    
    public function show(Request $request, $userId)
    {
        if(auth('api')->user()->id != $userId)
            return response()->json(['message' => 'Unauthorized!'], 401);;
        $user = User::find($userId);
        $user->addresses;
        
        if($user) {
            
            return response()->json($user);
        }

        return response()->json(['message' => 'User not found!'], 404);
    
    }
}
