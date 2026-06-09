@extends('layouts.app')

@section('title','Manajemen User')
@section('page-title','Manajemen User')

@section('content')

<div class="card">

    <div class="card-header">

        <span class="card-title">
            Daftar User
        </span>

        <a href="{{ route('users.create') }}"
           class="btn btn-primary">

            + Tambah User

        </a>

    </div>

    <div class="table-wrap">

        <table>

            <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th width="250">Aksi</th>
            </tr>
            </thead>

            <tbody>

            @foreach($users as $user)

            <tr>

                <td>{{ $user->name }}</td>

                <td>{{ $user->email }}</td>

                <td>
                    {{ ucfirst($user->role) }}
                </td>

                <td>

                    @if($user->is_active)

                        <span class="badge badge-paid">
                            Aktif
                        </span>

                    @else

                        <span class="badge badge-overdue">
                            Nonaktif
                        </span>

                    @endif

                </td>

                <td>

                    <a href="{{ route('users.edit',$user) }}"
                       class="btn btn-outline btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('users.toggle-active',$user) }}"
                        method="POST"
                        style="display:inline">

                        @csrf

                        <button class="btn btn-warning btn-sm">

                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}

                        </button>

                    </form>

                    <form
                        action="{{ route('users.destroy',$user) }}"
                        method="POST"
                        style="display:inline">

                        @csrf
                        @method('DELETE')

                        <button
                            onclick="return confirm('Hapus user?')"
                            class="btn btn-danger btn-sm">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection