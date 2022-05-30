<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    
//microtic
//elpic for linux
//voip
    /**
     * curl  http://localhost:8000/user/create -d "name=Zahara&phone=04798773657&city=Jam"
     */
    public function addUser(Request $request)
    {
        $validValues = ['name', 'phone', 'city'];
        if (!$request->has($validValues))
            return response()->json( data: ['error' => 'not enough values!'], status: 401);

        $values = $request->only($validValues);

        $user = new User;
        $user->name = $values['name'];
        $user->phone = $values['phone'];
        $user->city = $values['city'];
        $user->save();
        return response()->json( data: array_merge(['success' => 'Successfully created a new user'], $values) );
    }

    public function getUsers(Request $request)
    {
        return response()->json(data: User::all());
    }

    public function getUser($id)
    {
        return response()->json(data: User::findOr($id,fn()=>['error'=>'no matched user!']), status: 500);
    }

    public function setUser(Request $request, $id)
    {
        $validValues = ['name', 'phone', 'city'];
        $user = User::find($id);
        if(!$user)
            return response()->json( data: ['error'=>'no matched user!'], status: 500);
        $result = $user->update($request->only($validValues));
        if($result===false)
            return response()->json( data: ['error'=>'curropted a fail on server'], status: 500);
        return response()->json( data: ['success'=>'sccessfully updated user!'], status: 200);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $response = new Response();
        $response -> header('Content-Type','application/json');
        if(!$user)
            return response()->json( data: ['error'=>'no matched user!'], status: 500);
        
        if(!$user->delete())
            return response()->json( data: ['error'=>'fails on server!'], status: 500);
        
        return response()->json( data: ['success'=>'sccessfully deleted the user!'], status: 200);
    }
}
