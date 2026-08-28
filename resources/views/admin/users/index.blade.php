@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="userManagement()">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-black">User Management</h2>
        <button type="button" @click="openCreateModal()" class="bg-black text-white px-4 py-2 text-sm font-bold border border-black hover:bg-slate-800 transition-colors">
            + New User
        </button>
    </div>

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-500 text-rose-700 px-4 py-3 text-sm font-bold">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-500 text-emerald-700 px-4 py-3 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="dev-card overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="border-b border-black bg-[#f8f9fa] text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold text-black border-r border-black w-10 text-center">ID</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">User</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Role</th>
                    <th class="px-6 py-4 font-bold text-black border-r border-black">Joined At</th>
                    <th class="px-6 py-4 font-bold text-black text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-bold text-slate-500 border-r border-black text-center">{{ $user->id }}</td>
                        <td class="px-6 py-4 border-r border-black">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" alt="avatar" class="w-8 h-8 rounded-full border border-black">
                                <div>
                                    <div class="font-bold text-black">{{ $user->name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $user->email }} • {{ '@'.$user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-r border-black">
                            <span class="px-2 py-1 text-[10px] font-bold uppercase border border-black {{ $user->role === 'admin' ? 'bg-black text-white' : 'bg-[#f8f9fa] text-black' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 border-r border-black text-xs">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-right space-x-3">
                            <button type="button" @click="openInfoModal({{ $user->toJson() }})" class="text-emerald-600 hover:underline font-bold text-xs">Info</button>
                            <button type="button" @click="openEditModal({{ $user->toJson() }})" class="text-blue-600 hover:underline font-bold text-xs">Edit</button>
                            @if(auth()->id() !== $user->id)
                                @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-amber-600 hover:underline font-bold text-xs">Impersonate</button>
                                </form>
                                @endif
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this user permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:underline font-bold text-xs">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    {{-- MODALS --}}
    <!-- Create/Edit Modal -->
    <div x-show="isFormModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="closeModal()">
        <div class="bg-white border-2 border-black p-6 w-full max-w-md shadow-[4px_4px_0_0_#000] flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between mb-4 border-b-2 border-black pb-2">
                <h3 class="text-lg font-bold text-black" x-text="isEdit ? 'Edit User' : 'Create New User'"></h3>
                <button @click="closeModal()" type="button" class="text-black hover:bg-slate-100 p-1 font-bold">✕</button>
            </div>

            <form :action="formAction" method="POST" class="space-y-4 overflow-y-auto pr-2 console-scroll">
                @csrf
                <template x-if="isEdit">
                    @method('PUT')
                </template>

                <div>
                    <label class="block text-xs font-bold text-black mb-1">Name</label>
                    <input type="text" name="name" x-model="formData.name" required class="w-full border border-black p-2 text-sm text-black focus:outline-none focus:border-black bg-[#f8f9fa]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Username</label>
                    <input type="text" name="username" x-model="formData.username" required class="w-full border border-black p-2 text-sm text-black focus:outline-none focus:border-black bg-[#f8f9fa]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Email</label>
                    <input type="email" name="email" x-model="formData.email" required class="w-full border border-black p-2 text-sm text-black focus:outline-none focus:border-black bg-[#f8f9fa]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Role</label>
                    <select name="role" x-model="formData.role" required class="w-full border border-black p-2 text-sm text-black focus:outline-none focus:border-black bg-white appearance-none cursor-pointer">
                        <option value="student">Student</option>
                        <option value="lecturer">Lecturer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Password</label>
                    <input type="password" name="password" class="w-full border border-black p-2 text-sm text-black focus:outline-none focus:border-black bg-[#f8f9fa]" :placeholder="isEdit ? 'Leave blank to keep current' : 'Min 8 characters'" :required="!isEdit">
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t border-black">
                    <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-bold text-black border border-black hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="bg-black text-white px-4 py-2 text-sm font-bold border border-black hover:bg-slate-800" x-text="isEdit ? 'Save Changes' : 'Create User'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Modal -->
    <div x-show="isInfoModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="closeModal()">
        <div class="bg-white border-2 border-black p-6 w-full max-w-md shadow-[4px_4px_0_0_#000]">
            <div class="flex items-center justify-between mb-4 border-b-2 border-black pb-2">
                <h3 class="text-lg font-bold text-black">User Info</h3>
                <button @click="closeModal()" type="button" class="text-black hover:bg-slate-100 p-1 font-bold">✕</button>
            </div>

            <div class="space-y-4" x-show="selectedUser">
                <div class="flex items-center gap-4 border border-black p-4 bg-[#f8f9fa]">
                    <img :src="selectedUser?.avatar_url" class="w-12 h-12 rounded-full border border-black bg-white">
                    <div>
                        <div class="font-bold text-black text-lg" x-text="selectedUser?.name"></div>
                        <div class="text-xs text-slate-600" x-text="selectedUser?.email"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="border border-black p-3 text-center bg-white">
                        <div class="text-2xl font-bold text-black" x-text="selectedUser?.papers_count || 0"></div>
                        <div class="text-[10px] font-bold uppercase text-slate-500">Papers</div>
                    </div>
                    <div class="border border-black p-3 text-center bg-white">
                        <div class="text-2xl font-bold text-black" x-text="selectedUser?.makalah_count || 0"></div>
                        <div class="text-[10px] font-bold uppercase text-slate-500">Makalah</div>
                    </div>
                    <div class="border border-black p-3 text-center bg-white">
                        <div class="text-2xl font-bold text-black" x-text="selectedUser?.notes_count || 0"></div>
                        <div class="text-[10px] font-bold uppercase text-slate-500">Notes</div>
                    </div>
                    <div class="border border-black p-3 text-center bg-white">
                        <div class="text-2xl font-bold text-black" x-text="selectedUser?.subjects_count || 0"></div>
                        <div class="text-[10px] font-bold uppercase text-slate-500">Subjects Enrolled</div>
                    </div>
                </div>
            </div>
            
            <div class="pt-4 mt-4 flex justify-end border-t border-black">
                <button type="button" @click="closeModal()" class="px-4 py-2 text-sm font-bold border border-black hover:bg-slate-100">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.userManagement = function() {
        return {
            isModalOpen: false,
            isFormModalOpen: false,
            isInfoModalOpen: false,
            isEdit: false,
            formAction: '',
            selectedUser: null,
            formData: {
                name: '',
                username: '',
                email: '',
                role: 'student'
            },
            
            openCreateModal() {
                this.isEdit = false;
                this.formAction = '{{ route("admin.users.store") }}';
                this.formData = { name: '', username: '', email: '', role: 'student' };
                this.isModalOpen = true;
                this.isFormModalOpen = true;
            },
            
            openEditModal(user) {
                this.isEdit = true;
                this.formAction = `/admin/users/${user.id}`;
                this.formData = {
                    name: user.name,
                    username: user.username,
                    email: user.email,
                    role: user.role
                };
                this.isModalOpen = true;
                this.isFormModalOpen = true;
            },
            
            openInfoModal(user) {
                this.selectedUser = user;
                this.isModalOpen = true;
                this.isInfoModalOpen = true;
            },
            
            closeModal() {
                this.isModalOpen = false;
                this.isFormModalOpen = false;
                this.isInfoModalOpen = false;
                this.selectedUser = null;
            }
        };
    };
</script>
@endpush
@endsection
