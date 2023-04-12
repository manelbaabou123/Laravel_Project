<x-guest-layout>
  <x-input-label for="New Project" :value="__('New Project')" class="text-center dark:hover:text-gray-100"/>
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
  <form action="{{ route('project.store') }}" method="POST" class="form-group">
      @csrf

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
        <a href="{{ route('project.index') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center">Back</a>
          <x-primary-button class="ml-4">
            Create
          </x-primary-button>
          
      </div>
  </form>
</x-guest-layout>