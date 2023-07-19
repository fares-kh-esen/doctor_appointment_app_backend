<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\User;
use App\Models\Groomer;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = array(); //this will return a set of user and groomer data
        $user = Auth::user();
        // $groomer = User::with('user_details')->where('type', 'groomer')->get();
        $groomers = Groomer::with('user')->where('groomer_id' , '<>' , $user->id)->get();
        //this is the date format without leading

        //make this appointment filter only status is "upcoming"
        $appointment = Appointments::with('groomer.user')->where([
            'status' => 'upcoming',
            'user_id' => $user->id
            ])->whereDate('date', '=' ,Carbon::today()->toDateString())->first();

        //collect user data and all groomer details
        // foreach($groomerData as $data){
        //     //sorting groomer name and groomer details
        //     foreach($groomer as $info){
        //         if($data['groomer_id'] == $info['id']){
        //             $data['groomer_name'] = $info['name'];
        //             $data['groomer_profile'] = $info['profile_photo_url'];
        //             $data['groomer_profile'] = $info['profile_photo_url'];
        //             $data['groomer_profile'] = $info['profile_photo_url'];
        //             if(isset($appointment) && $appointment['groomer_id'] == $info['id']){
        //                 $data['appointments'] = $appointment;
        //             }
        //         }
        //     }
        // }

        // $user['groomer'] = $groomerData;

        return response([
            'appoitment' => $appointment,
            'user' => $user,
            'groomers' => $groomers,
        ] , 200); //return all data
    }

    /**
     * loign.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $reqeust)
    {
        //validate incoming inputs
        $reqeust->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        //check matching user
        // return 1;

        //check password
        if (!Auth::attempt(request()->only(['email', 'password']))) {
            return response()->json([
                'error' => true,
                'message' => 'Email & invalid passwords!',
            ], 200);
        }
        $user = User::where('email', $reqeust->email)->first();

        //then return generated token
        return $user->createToken($reqeust->email)->plainTextToken;
    }

    /**
     * register.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        //validate incoming inputs
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'type'=>'user',
            'password'=>Hash::make($request->password),
        ]);

        $userInfo = UserDetails::create([
            'user_id'=>$user->id,
            'status'=>'active',
        ]);

        return $user;


    }

    /**
     * update favorite groomer list
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFavgroomer(Request $request)
    {

        $saveFav = UserDetails::where('user_id',Auth::user()->id)->first();

        $groomerList = json_encode($request->get('favList'));

        //update fav list into database
        $saveFav->fav = $groomerList;  //and remember update this as well
        $saveFav->save();

        return response()->json([
            'success'=>'The Favorite List is updated',
        ], 200);
    }

    /**
     * logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(){
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'success'=>'Logout successfully!',
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
