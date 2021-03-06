<?php

namespace qnectu\Qauth\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserRole;
use DB;

class QauthController extends Controller
{
    public function index(){
        return 'Congrats. Finally package is working';
    }

/**
 * Handles Login Request
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function login(Request $request)
    {
        $request = (object) $request->json()->all();
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('TutsForWeb')->accessToken;
            return response()->json(['success' => true,'token' => $token], 200);
        } else {
            return response()->json(['success' => false,'error' => 'UnAuthorised'], 401);
        }
    }

    /**
* add new user
*
*/
    public function adduser(Request $request)
    {
        $request = (object) $request->json()->all();
        try {
            $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            ]);
            return response()->json(['success' => true,'message'=> 'user added successfully'], 200);
        } catch (\Exception $e) {
            if($e){
            return response()->json(['success' => false,'message'=> 'Something went wrong','error'=> $e], 200);
            }
        }
    }

/**
* get all user list from database
*
*/
    public function getalluser(User $user)
    {
    return DB::table('users')
    ->select('*')
    ->join('user_roles','user_roles.id','=','users.role_id')
    ->get();
    }

/**
 * Returns Authenticated User Details
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
/**
 * Get All user roles
 */
    public function getallroles()
    {
        return DB::table('user_roles')
        ->select('*')
        ->get();
        // return response()->json(UserRole::all());
    }
}
