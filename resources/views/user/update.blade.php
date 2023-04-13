<x-guest-layout>
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ __('User Information') }}
      </h2>
  
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __("Update User information") }}
      </p>
  </header>
            @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
            @endif
  
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
         
    <form action="{{ route('user.update', $user) }}" method="POST" class="mt-6 space-y-6">
        @csrf
<hr>
        {{-- <!-- Role -->
        <div class="form-group  dark:text-gray-400">
          <input type="text" name="id" style="display: none;" value="{{ $user->id }}">
            <div class="form-group">
                <label for="User" class="form-label ">Select User</label>
              <select name="role_id" class="form-control">
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}" {{ $user->roles->contains($role->id ) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
              </select>
            </div> --}}

            <!-- Role -->
      <div class="mt-4">
        <div class="flex items-center justify mt-4">
            <div class="form-group  dark:text-gray-400">
                <x-input-label for="role" :value="__('Role :')" />
                @foreach ($roles as $role)
                <input type="checkbox" name="role_id[]" value="{{ $role->id }}" {{ $user->roles->contains($role->id ) ? 'checked' : '' }}>
                  {{ $role->name }}
                @endforeach
            </div>
        </div>
      </div>
   
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name :')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $user->name }}"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email :')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ $user->email }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password :')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password :')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        
        <div class="flex items-center justify-end mt-4">
          <a href="{{ route('user.index') }}" type="submit" class="btn btn-info dark:text-gray-400 text-center">Back</a>
            <x-primary-button class="ml-4">
              Update
            </x-primary-button>
        </div>

    </form>
  </x-guest-layout>