<x-guest-layout>
  <header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Task Information') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __("Update Task information") }}
    </p>
</header>
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
  <form action="{{ route('task.update', $task) }}" method="POST" class="mt-6 space-y-6">
      @csrf

      @can('view', \App\Models\User::class)
      <div class="form-group  dark:text-gray-400">
        <input type="text" name="id" style="display: none;" value="{{ $task->id }}">
          <div class="form-group">
              <label for="User" class="form-label ">Select User</label>
            <select name="user_id" class="form-control">
              @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
              @endforeach
            </select>
          </div>
          @endcan
    
      <div class="form-group  dark:text-gray-400">
        <input type="text" name="id" style="display: none;" value="{{ $task->id }}">
          <div class="form-group">
              <label for="Project" class="form-label ">Select Project</label>
            <select name="project_id" class="form-control">
              @foreach ($projects as $project)
                <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
              @endforeach
            </select>
          </div>
        

          <div class="flex items-center justify mt-4">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Task')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $task->name }}" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
          </div>
      
            <!-- Description -->
          <div>
            <x-input-label for="Description" :value="__('Description')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" value="{{ $task->description }}"/>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
          </div>
      
      <div class="flex items-center justify-end mt-4">
        <a href="{{ route('task.index') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center">Back</a>
          <x-primary-button class="ml-4">
            Save
          </x-primary-button>
      </div>
      </div>
  </form>
</x-guest-layout>