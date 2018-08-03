<?php

namespace App\Http\Controllers;

use Token;
use App\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->servers()->latest()->get();
    }

    /**
     * Undocumented function
     *
     * @param Server $server
     * @return void
     */
    public function show(Server $server, Request $request)
    {
        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'You are not authorized to view this server!'
            ], 403);
        }

        return $server->load('receivers');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required'
        ]);

        $server = $request->user()->servers()->create([
            'title' => $request->input('title'),
            'token' => Token::unique('servers', 'token', 32), // Generate unique token
            'status' => ($request->input('active') == 'active') ? 'active' : 'inactive'
        ]);

        return $server;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Server $server
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required'
        ]);

        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'You are not authorized to update this server!'
            ], 403);
        }

        $server->update([
            'title' => $request->input('title'),
            'status' => ($request->input('active') == 'active') ? 'active' : 'inactive'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Server $server
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Request $request)
    {
        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'You are not authorized to delete this server!'
            ], 403);
        }

        $server->delete();
    }
}
