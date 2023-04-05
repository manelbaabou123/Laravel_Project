<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TO DO UP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>


    <body>
        <div class="container text-center">
            <div class="row">
              <div class="col s12" >
                <hr><hr>
                <h2>Task</h2>
                <hr>
                <a href="{{ route('task.create') }}" class='btn btn-primary'>Create Task</a>
                <hr>
    
                @if (session('status'))
                <div class="alert alert-success"> 
                    {{ session('status') }}
                </div>
                @endif
    
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Project</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      @php
                        $ide = 1;
                      @endphp
                      @foreach ($tasks as $task )
                        <tr>
                            <td>{{ $ide }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->project->name }}</td>
                            <td>                    
                                <a href="{{ route('task.edit', $task) }}" class="btn btn-info">Update</a>
                                <a href="{{ route('task.destroy', $task) }}" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        @php
                          $ide += 1;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
                {{ $tasks->links() }}
              </div>
            </div>
          </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>
</html>