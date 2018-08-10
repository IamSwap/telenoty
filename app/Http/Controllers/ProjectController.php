<?php

namespace App\Http\Controllers;

use Token;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->projects()->latest()->get();
    }

    /**
     * Get project info.
     *
     * @param  \App\Project  $project
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Request $request)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['message' => 'Unauthorized Request!'], 403);
        }

        return $project;
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
            'title' => 'required'
        ]);

        return $request->user()->projects()->create([
            'title' => $request->input('title'),
            'token' => Token::unique('projects', 'token', 16),
            'status' => 'active'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required'
        ]);

        if ($request->user()->id != $project->user_id) {
            return response()->json(['message' => 'Unauthorized Request!'], 403);
        }

        $project->update([
            'title' => $request->input('title'),
            'status' => ($request->input('status') == 'active') ? 'active' : 'inactive'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Request $request)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['message' => 'Unauthorized Request!'], 403);
        }

        $project->delete();
    }
}
