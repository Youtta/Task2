@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Add Profile
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ $isEdit ? route('profile.update',[$profile->id]) : route('profile.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($isEdit)
                        <input type="hidden" name="_method" value="put">
                         @endif
                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name"  value="{{$profile->name ?? null }}" required>
                          <span class="text-danger">{{ $errors->first('name') ?? null }}</span>
                        </div>
                        <div class="form-group">
                          <label for="gender">Gender</label>
                          <input type="text" name="gender" class="form-control" id="Gender" placeholder="Gender" value="{{$profile->gender ?? null }}" required>
                          <span class="text-danger">{{ $errors->first('gender') ?? null }}</span>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                          <input type="number" name="age" class="form-control" id="age" placeholder="Age" value="{{$profile->age ?? null }}" required>
                          <span class="text-danger">{{ $errors->first('age') ?? null }}</span>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" class="form-control" id="image" value="{{$profile->image ?? null }}" required>
                            <span class="text-danger">{{ $errors->first('image') ?? null }}</span>
                          </div>
                          <br>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
