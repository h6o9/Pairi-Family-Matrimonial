@extends('marriage_bureau.master_layout')
@section('title')
<title>Edit User - Piyari Family</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit User: {{ $user->name }}</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('marriage-bureau.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Password (Leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Country</label>
                                <input type="text" name="country" class="form-control" value="{{ $user->country }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="{{ $user->city }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning mt-3">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
