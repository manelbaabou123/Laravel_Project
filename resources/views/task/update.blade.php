<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TO DO UP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>

    <hr>
    <div class="container">
        <div class="row">
          <div class="col s12" >
            <h1>Update Task</h1>
            <hr>

            @if (session('status'))
                <div class="alert alert-success"> 
                    {{ session('status') }}
                </div>
            @endif

            <ul>
            @foreach ($errors->all() as $error)
                <li class="alert alert-danger">{{ $error }}</li>
            @endforeach
            </ul>

      
            <hr>
           
            <form action="{{ route('task.update', $task) }}" method="POST" class="form-group">
                
                @csrf

                <input type="text" name="id" style="display: none;" value="{{ $task->id }}">

                <div class="form-group">
                  <label for="Project" class="form-label">Select Project</label>
                <select name="project_id" class="form-control">
                  @foreach ($projects as $project)
                  <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                  @endforeach
                </select>
              </div>
                <div class="form-group">
                  <label for="Name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="Name" name="name" value="{{ $task->name }}">
                </div>
                <div class="form-group">
                  <label for="Description" class="form-label">Description</label>
                  <input type="text" class="form-control" id="Description" name="description" value="{{ $task->description }}">
                </div>
                  <hr>
                  <hr>
                <button type="submit" class="btn btn-primary">Update Task</button>
                <a href="{{ route('task.index') }}" type="submit" class="btn btn-info text-center">Back</a>

              </form>

          </div>
        </div>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
</html>