<x-guest-layout>
  <x-input-label for="New Task" :value="__('New Task')" class="text-center dark:hover:text-gray-100"/>
  <hr><hr><hr>

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
  <form action="{{ route('task.store') }}" method="POST" class="form-group">
      @csrf

      @can('view', \App\Models\User::class)
      <!-- User -->
      <div class="flex items-center justify mt-4">
        <div class="form-group  dark:text-gray-400">
          <label for="User" class="form-label block mt-1 w-full">User</label>
          <select name="user_id" class="form-control">
            <option disabled selected>Select object</option>
            @foreach ($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      @endcan
    <!-- Project -->
    <div class="flex items-center justify mt-4">
      <div class="form-group  dark:text-gray-400">
        <label for="Project" class="form-label block mt-1 w-full">Project</label>
        <select name="project_id" class="form-control">
          <option disabled selected>Select object</option>
          @foreach ($projects as $project)
            <option value="{{ $project->id }}">{{ $project->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

      <div class="flex items-center justify mt-4">
          <!-- Name -->
          <div>
              <x-input-label for="name" :value="__('Name')" />
              <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
          </div>
      </div>  
    
        <!-- Description -->
        <div>
            <x-input-label for="Description" :value="__('Description')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required autofocus autocomplete="description" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
      

      <div class="flex items-center justify-end mt-4">
        <a href="{{ route('task.index') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center">Back</a>
          <x-primary-button class="ml-4">
            Create
          </x-primary-button>
      </div>
  </form>
</x-guest-layout>