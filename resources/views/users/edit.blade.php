@extends('layouts.app')

@section('title','Edit User')
@section('page-title','Edit User')

@section('content')

<form action="{{ route('users.update',$user) }}"
      method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        <div class="card-body">

            <div class="field">
                <label>Nama</label>

                <input type="text"
                       name="name"
                       value="{{ $user->name }}">
            </div>

            <div class="field">
                <label>Email</label>

                <input type="email"
                       name="email"
                       value="{{ $user->email }}">
            </div>

            <div class="field">

                <label>Role</label>

                <select name="role">

                    <option value="owner"
                        {{ $user->role=='owner'?'selected':'' }}>
                        Owner
                    </option>

                    <option value="admin"
                        {{ $user->role=='admin'?'selected':'' }}>
                        Admin
                    </option>

                    <option value="staff"
                        {{ $user->role=='staff'?'selected':'' }}>
                        Staff
                    </option>

                </select>

            </div>

            <button class="btn btn-primary">
                Update User
            </button>

        </div>

    </div>

</form>

@endsection
