<x-app-layout>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('List Tasks') }}
      </h2>
  </x-slot>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <a href="{{ route('dashboard') }}" type="submit" class="fa fa-home mt-4 dark:text-gray-400"></a>
    </div>
  <div class="py-12">
 
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
              <a href="{{ route('task.create') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center" style="width: 1100px; margin: 0 auto;">Create Task</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
               @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                <style>
                  table {
                    display: table;
                    border-collapse: separate;
                    border-spacing: 10px;
                    border-color: gray;
                  }
                  </style>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>User</th>
                            <th>Task</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                   
                      <hr>
                      @foreach ($tasks as $task )
                        <tr>
                           <td>{{ ++$loop->index }}</td>
                            <td>{{ $task->project->name }}</td>
                            <td>{{ $task->user->name }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->description }}</td>
                            <td>
                                <a href="{{ route('task.edit', $task) }}" class="fa fa-edit"></a>
                                <a href="{{ route('task.destroy', $task) }}" class="fa fa-trash"></a>
                            </td>
                        </tr>
                       
                        @endforeach
                    </tbody>
                   
                </table>
                <hr>
                {{ $tasks->links() }}
              </div>
            </div>
          </div>
</x-app-layout>