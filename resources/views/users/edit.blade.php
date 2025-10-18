@extends("layouts.app")

@section("title", "Edit User")

@section("content")
    <form action="{{ route("users.update", $user) }}" class="space-y-4 rounded-2xl bg-white p-6 shadow" method="POST">
        @csrf()
        @method("PUT")
        <h1>Edit User</h1>
        <div class="flex flex-col">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" class="border border-gray-300 p-2">
        </div>
        <div class="flex flex-col">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}"
                class="border border-gray-300 p-2">
        </div>
        <div class="flex flex-col">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="{{ $user->phone }}"
                class="border border-gray-300 p-2">
        </div>
        <div class="flex flex-col">
            <label for="role">Role</label>
            <select type="text" id="role" name="role" value="{{ $user->role }}"
                class="border border-gray-300 p-2">
                <option value="Housekeeper" {{ $user->role == "Housekeeper" ? "selected" : "" }}>Housekeeping Staff
                </option>
                <option value="Admin" {{ $user->role == "Admin" ? "selected" : "" }}>Admin</option>
                <option value="Owner" {{ $user->role == "Owner" ? "selected" : "" }}>Owner</option>
            </select>
        </div>
        @if ($errors->any())
            <div class="text-sm text-red-500">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit" class="rounded-md bg-gray-700 p-2 text-white hover:bg-gray-600">Save</button>
    </form>
@endsection
