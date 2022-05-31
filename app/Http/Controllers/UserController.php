<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    function __construct(){
        $this->middleware('auth',['except'=>[
                'signIn','signUp','authUser'
            ]
        ]);
    }
    

    //microtic
    //elpic for linux
    //voip

    public function signIn(Request $request){
        $uniquer = [];
        //handle inputs variables
        if($request->filled('name'))
            $uniquer = [ 'name'=>'name','value'=>($request->name)];
        else if($request->filled('phone'))
            $uniquer = [ 'name'=>'phone','value'=>($request->phone)];
        else
            return response()->json(['error'=>'not enough values (give your name/phone first)!'],401);
        
        if(!$request->filled('password'))
            return response()->json(['error'=>'not enough values (give your password first)!'],401);
        
        $user = User::where($uniquer['name'],$uniquer['value'])->first();

        if(!$user)
            return response()->json(['error'=>'there are no user with '.implode('=',$uniquer)],501);


        $md5Password = md5($request->password);

        if($user->password !== $md5Password)
            return response()->json(['error'=>'not correct password!'],504);

        $user->api_token=null;
        $user->api_token=$this->authUser($user);
        $user->save();
        return response()->json(['success'=>'successfully signed in !']);
    }
    public function signUp(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'password'=>'required'
        ]);
        if(User::where('name',$request->name)->exists())
            return response()->json(['error'=>'same user with the same name registered in past!'],502);

        if(User::where('phone',$request->phone)->exists())
            return response()->json(['error'=>'same user with the same phone registered in past!'],502);

        $user = new User;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password = md5($request->password);
        $apiToken = $this->authUser($user);
        $user->api_token = $apiToken;
        $user->save();


        return response()->json(array_merge(['success'=>true],$user->getVisible()));
    }

    private function authUser($user){
        if($user->api_token != null)
            return null;
        
        $apiToken = md5($user->name.$user->phone);
        setcookie("api_token",$apiToken,time()+1*60*60*24*10 /*10 days*/);
        return $apiToken;
    }

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

        return response()->json(data: ['you'=>$request->user(),"all"=>User::all()]);
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

    public function authenticate(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'password'=>'required'
        ]);

        $user = User::where('')->first();
    }
}
