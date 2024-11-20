@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h2 class="text-2xl font-bold mb-4">Your Profile</h2>
    <div class="mb-4">
        <label class="text-700 font-bold mb-2" for="name">Name</label>
        <p class="text-700 font-italic mb-2">{{ $user->name }}</p>
    </div>
    <div class="mb-4">
        <label class="text-700 font-bold mb-2" for="email">Email</label>
        <p class="text-700 font-italic mb-2">{{ $user->email }}</p>
    </div>
    <div class="mb-4">
        <label class="text-700 font-bold mb-2" for="username">Username</label>
        <p class="text-700 font-italic mb-2">{{ $user->username }}</p>
    </div>
    <div class="mb-4">
        <label class="text-700 font-bold mb-2" for="location">Location</label>
        <p class="text-700 font-italic mb-2">{{ $user->location }}</p>
    </div>
    <div class="flex items-center justify-between">
        <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Back to Weather
        </a>
        <button onclick="openEditModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Edit Profile
        </button>
        <button onclick="confirmDelete()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Delete Account
        </button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Logout
            </button>
        </form>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Profile</h3>
            <div class="mt-2 px-7 py-3">
                <form id="editForm" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                        <input type="text" name="username" id="username" value="{{ $user->username }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                        <input type="text" name="location" id="location" value="{{ $user->location }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function confirmDelete() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            // Send a POST request to delete the account
            fetch('{{ route("profile.delete") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Your account has been deleted successfully.');
                    window.location.href = '{{ route("home") }}';
                } else {
                    alert('An error occurred while deleting your account.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting your account.');
            });
        }
    }
</script>
@endsection