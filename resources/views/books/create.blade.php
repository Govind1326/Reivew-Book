@extends('layouts.app')
@section('title')
New Book
@endsection
@section('main')

<div class="container">
    <div class="row my-5">
        @include('layouts.sidebar')
        <div class="col-md-9">
            @if (Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
            @endif
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Add Book
                </div>
                <div class="card-body">
                    <form action="{{route('books.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{@$book->id}}">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Title" value="{{@$book->title}}" name="title" id="title" />
                        @error('title')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" placeholder="Author" name="author" value="{{@$book->author}}" id="author" />
                        @error('author')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Description"
                            cols="30" rows="5">{{@$book->description}}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="Image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('iamge') is-invalid @enderror" name="image" id="image" />
                        @error('image')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" class="text-success" {{ @$book->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" class="text-danger" {{ @$book->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button class="btn btn-primary mt-2">{{@$book->id ? 'Update':'Create'}}</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection