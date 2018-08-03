<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($request->user()->id)
            ],
        ]);

        $request->user()->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
    }

    /**
     * Update user profile password
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if (! Hash::check($request->input('old_password'), $request->user()->password)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'old_password' => ['Old password is incorrect!']
                ]
            ], 400);
        }

        $request->user()->update([
            'password' => bcrypt($request->input('password')),
        ]);
    }
}
