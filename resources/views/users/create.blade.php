@extends('layouts.app')

@section('title','Tambah User')
@section('page-title','Tambah User')

@section('content')

<form action="{{ route('users.store') }}"
      method="POST">

    @csrf

    <div class="card">

        <div class="card-body">

            <div class="field">
                <label>Nama</label>
                <input type="text"
                       name="name"
                       required>
            </div>

            <div class="field">
                <label>Email</label>
                <input type="email"
                       name="email"
                       required>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password"
                       name="password"
                       required>
            </div>

            <div class="field">
                <label>Role</label>

                <select name="role">

                    <option value="owner">
                        Owner
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                    <option value="staff">
                        Staff
                    </option>

                </select>

            </div>

            <button class="btn btn-primary">

                Simpan

            </button>

        </div>

    </div>

</form>

@endsection