<x-app-layout>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('List Projects') }}
      </h2>
  </x-slot>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <a href="{{ route('dashboard') }}" type="submit" class="fa fa-home mt-4 dark:text-gray-400"></a>
    </div>
  <div class="py-12">
  @can('view', \App\Models\Project::class)
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
              <a href="{{ route('project.create') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center" style="width: 1100px; margin: 0 auto;">Create Project</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endcan
  
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
               @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                <hr>
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
                          <th>Description</th>
                      </tr>
                  </thead>
                  <tbody>
                    
                    @foreach ($projects as $project )
                      <tr>
                        <td>{{ ++$loop->index }}</td>
                          <td>{{ $project->name }}</td>
                          <td>{{ $project->description }}</td>
                          <td>
                            @can('update', $project)
                            <a href="{{ route('project.edit', $project) }}" class="fa fa-edit "></a>
                            @endcan
                            @can('delete', $project)
                            <a href="{{ route('project.destroy', $project) }}" class="fa fa-trash mt-4 "></a>
                            @endcan
                          </td>
                      </tr>
                      
                      @endforeach
                  </tbody>
              </table>
                <hr>
                {{ $projects->links() }}
              </div>
            </div>
          </div>
  
</x-app-layout>
