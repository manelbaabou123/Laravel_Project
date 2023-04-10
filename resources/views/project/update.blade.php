<x-guest-layout>
  <header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Project Information') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __("Update Project information") }}
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
          </ul>
          <hr>
    <form action="{{ route('project.update', $project) }}" method="POST" class="mt-6 space-y-6">
      @csrf

      <input type="text" name="id" style="display: none;" value="{{ $project->id }}">

      <div class="flex items-center justify mt-4">
          <!-- Name -->
          <div>
              <x-input-label for="name" :value="__('Name')" />
              <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $project->name }}"/>
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
          </div>
      </div>  
    
          <!-- Description -->
          <div>
            <x-input-label for="Description" :value="__('Description')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" value="{{ $project->description }}"/>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

      <div class="flex items-center justify-end mt-4">
        <a href="{{ route('project.index') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center">Back</a>
          <x-primary-button class="ml-4">
            Save
          </x-primary-button>
      </div>
  </form>
</x-guest-layout>