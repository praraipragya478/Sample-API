<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|string|min:2|max:255',
        //     'email' => 'required|string|email:rfc,dns|max:255|unique:users',
        //     'password' => 'required|string|min:4|max:255',
        // ]);

        // if the request valid, create user

        $user = $this->user::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'type' => 'admin',
            'phone' => $request['phone'],
        ]);

        $token = auth()->login($user);

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User created successfully!',
            ],
            'data' => [
                'user' => $user,
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60,   
                ],
            ],
        ]);
    }

    public function login(Request $request)
    {
        if($request->isMethod('post'))
        {
            $token = auth()->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);
            if ($token)
            {
                $output = [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Quote fetched successfully.',
                    ],
                    'data' => [
                        'user' => auth()->user(),
                        'access_token' => [
                            'token' => $token,
                            'type' => 'Bearer',
                            'expires_in' => auth()->factory()->getTTL() * 60,
                        ],
                    ],
                ];
            }else {
                $output = [
                    'data'=>[]
                ];
            }
            return response()->json($output);
        }
        return view('auth.login');
    }

    public function logout()
    {
        $token = JWTAuth::getToken();
        $invalidate = JWTAuth::invalidate($token);
        if($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ]);
        }
    }  

    public function me() 
    {
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User fetched successfully!',
            ],
            'data' => [
                'user' => auth()->user(),
            ],
        ]);
    }

    // public function register()  
    // {
    //     return view('auth.register');
    // }

    public function signup(Request $request)
    {
        $user = $this->user::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);
        $token = auth()->login($user);
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User created successfully!',
            ],
            'data' => [
                'user' => $user,
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60, 
                ],
            ],
        ]);
    }


    public function refresh()
    { 
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
