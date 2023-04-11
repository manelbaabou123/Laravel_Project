<x-app-layout>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('List Members') }}
      </h2>
  </x-slot>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
  <a href="{{ route('dashboard') }}" type="submit" class="fa fa-home mt-4 dark:text-gray-400"></a>
  </div>
  
  @can('create', \App\Models\User::class)
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
              <a href="{{ route('user.create') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center" style="width: 1100px; margin: 0 auto;">Add new User</a>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  @endcan
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg ">
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
                          <th>Name</th><th></th>
                          <th>Email</th><th></th>
                          <!--<th>Task</th><th></th>-->
                          
                      </tr>
                  </thead>
                  <tbody>
                   
                    @foreach ($users as $user )
                      <tr>
                        <td>{{ ++$loop->index }}</td>
                          <td>{{ $user->name }}</td><td></td>
                          <td>{{ $user->email }}</td><td></td>
                          <td>{{ $user->task?->name }}</td><td></td>
                          <td>
                            @can('update', $user)
                              <a href="{{ route('user.edit', $user) }}" class="fa fa-edit"></a>
                            @endcan
                            @can('delete', $user)
                              <a href="{{ route('user.destroy', $user) }}" class="fa fa-trash"></a>
                            @endcan
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
              {{ $users->links() }}
              </div>
            </div>
          </div>
  
</x-app-layout>