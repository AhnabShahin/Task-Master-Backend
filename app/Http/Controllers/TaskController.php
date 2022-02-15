<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Image;
class TaskController extends Controller
{
    protected $user, $tasks;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->tasks = Task::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tasks()
    {   
        return $this->user
            ->tasks()
            ->get();
    }

  


    public function create(Request $request)
    {

        $data = $request->only('title', 'due_date', 'duration', 'type','progress');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'due_date' => 'required',
            'duration' => 'required',
            'type' => 'required',
            'progress' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $task = $this->user->tasks()->create([
            'title' => $request->title,
            'due_date' => $request->due_date,
            'duration' => $request->duration,
            'type' => $request->type,
            'progress' => $request->progress,
        ]);

        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], Response::HTTP_OK);
    }


    public function show($id)
    {
        $task = $this->user->tasks()->find($id);
        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task not found.'
            ], 400);
        }
    
        return $task;
    }


    public function edit(Task $task)
    {
        //
    }


    public function update(Request $request, Task $task)
    {
        //Validate data
        $data = $request->only( 'due_date', 'duration', 'type','progress');
        $validator = Validator::make($data, [
            'due_date' => 'required',
            'duration' => 'required',
            'type' => 'required',
            'progress' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update task
        $task = $task->update([
            'due_date' => $request->due_date,
            'duration' => $request->duration,
            'type' => $request->type,
            'progress' => $request->progress,
        ],
        ['timestamps' => true]);

        //Task updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'task updated successfully',
            'data' => $task
        ], Response::HTTP_OK);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'task deleted successfully'
        ], Response::HTTP_OK);
    }
    public function get_user()
    {
        return response()->json(['user' => $this->user]);
    }
}