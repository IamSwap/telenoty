<?php

namespace App\Http\Controllers;

use Token;
use Telegram;
use App\Project;
use App\Subscriber;
use App\ProjectSubscriber;
use Illuminate\Http\Request;

class ProjectSubscriberController extends Controller
{
    /**
     * Get project subscribers.
     *
     * @param  \App\Project  $project
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project, Request $request)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['message' => 'Unauthorized Request!'], 403);
        }

        return [
            'project' => $project,
            'subscribers' => $project->subscribers()->latest()->get()
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Project  $project
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, Request $request)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['message' => 'Unauthorized Request!'], 403);
        }

        $subscriber = $project->subscribers()->create([
            'token' => $this->getToken(),
            'status' => 'inactive'
        ]);

        return $subscriber;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param \App\ProjectSubscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, ProjectSubscriber $subscriber)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['message' => 'Unauthorized Request!'], 403);
        }

        $this->validate($request, [
            'status' => 'required'
        ]);

        if ($request->input('status') == 'active' && ! $subscriber->chat_id) {
            return response()->json([ 'message' => 'This subscriber can not be activated! Make sure that, it is authorized by a Telegram user first.' ], 400);
        }

        $subscriber->update([
            'status' => ($request->input('status') == 'active') ? 'active' : 'inactive'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param \App\ProjectSubscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project, ProjectSubscriber $subscriber)
    {
        if ($request->user()->id != $project->user_id) {
            return response()->json(['message' => 'Unauthorized Request!'], 403);
        }

        $subscriber->delete();
    }

    /**
     * Authorize the specified subscriber in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param \App\ProjectSubscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, Project $project, ProjectSubscriber $subscriber)
    {
        $this->validate($request, [
            'chat_id' => 'required',
            'name' => 'required',
            'username' => 'required'
        ]);

        $subscriber->update([
            'chat_id' => $request->input('chat_id'),
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'status' => 'active'
        ]);

        $message = '🎉 Successfully authorized to @TeleNotyBot. You are now able to receive Telegram deployment notifications from project ' . $project->title . '!';

        Telegram::sendMessage([
            'chat_id' => $subscriber->chat_id,
            'text' => $message,
            'parse_mode' => 'markdown'
        ]);
    }

    /**
     * Generate unique token
     * @return int
     */
    protected function getToken()
    {
        $token = Token::random(16);

        if (Subscriber::where('token', $token)->exists()) {
            return $this->getToken();
        }

        if (ProjectSubscriber::where('token', $token)->exists()) {
            return $this->getToken();
        }

        return $token;
    }
}
