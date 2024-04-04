<?php

namespace App\Http\Controllers;
use App\User;
use App\Folder;
use App\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //タスク一覧表示
    public function index(Folder $folder)
    {
        // ユーザのフォルダを取得する
        $folders = Auth::user()->folders()->get();

        /* 選ばれたフォルダを取得する
        $current_folder = Folder::find($id);

        対象のフォルダが存在しない場合
        if(is_null($current_folder)) {
            abort(404);
        }
        
        正しいユーザのアクセスでない場合
        if (Auth::user()->id !== $folder->user_id) {
            abort(403);
        }
        */

        // 選ばれたフォルダに紐づくタスクを取得する
        //▼旧取得方法
        //$tasks = Task::where('folder_id', $current_folder->id)->get();
        $tasks = $folder->tasks()->get();
        
        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
        ]);
    }

    //タスク作成画面表示
    public function showCreateForm(Folder $folder)
    {
        return view('tasks/create',[
            'folder_id' => $folder->id
        ]);
    }

    //タスク作成処理
    public function create(Folder $folder, CreateTask $request)
    {

        //
        //$current_folder = Folder::find($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $folder->tasks()->save($task);
        //

        /*
        $folder = Folder::find($id);
        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;
        $task->folder_id = $folder->id;
        $task->save();
        */

        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }

    //タスク編集画面表示
    public function showEditForm(Folder $folder, Task $task)
    {
        $this->checkRelation($folder, $task);

        //$task = Task::find($task_id);

        return view('tasks/edit', [
            'task' => $task,
        ]);
    }


    public function edit(Folder $folder, Task $task, EditTask $request)
    {
        $this->checkRelation($folder, $task);

        //$task = Task::find($task_id);

        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index', [
            'id' => $task->folder_id,
        ]);
    }

    private function checkRelation(Folder $folder, Task $task)
    {
        if($folder->id !== $task->folder_id){
            abort(404);
        }
    }
}
