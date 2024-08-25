@extends('layouts.app')
@section('title')
Book List
@endsection
@section('main')

    <div class="container">
        <div class="row my-5">
            @include('layouts.sidebar')

            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Books
                    </div>
                    <div class="card-body pb-0">            
                        <a href="{{route('books.create')}}" class="btn btn-primary">Add Book</a>            
                        <table class="table  table-striped mt-3" id="myBookListTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th width="70">Action</th>
                                </tr>
                                <tbody>
                                    @if($books->isNotEmpty())
                                    @foreach ($books as $book )
                                    <tr>
                                        <td><a href="{{route('book.bookdetail',['id'=>$book->id])}}">{{$book->title}}</a></td>
                                        <td>{{$book->author}}</td>
                                        <td>{{$book->reviews_avg_rating==0?'-':number_format($book->reviews_avg_rating, 1)}}</td>
                                        <td class="{{$book->status?'text-success':'text-danger'}}">{{$book->status?'Active':'Inactive'}}</td>
                                        <td>
                                            {{-- <a href="" class="btn btn-success btn-sm"><i class="fa-regular fa-star"></i></a> --}}
                                            <a href="{{ route('books.update', ['id' => $book->id]) }}" class="btn btn-primary btn-sm m-1"><i class="fa-regular fa-pen-to-square"></i></a>
                                            <a onclick="deleteb({{$book->id}})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6"> No books found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </thead>
                        </table>
                        @if($books->isNotEmpty())
                        {{$books->links()}}
                        @endif
                    </div>
                </div>                
            </div>
        </div>       
    </div>
    @endsection
    @section('script')
    <script>
        $(document).ready(function(){
            $('#myBookListTable').DataTable({
            "paging": false, // Enable pagination
            "searching": false, // Enable search box
            "ordering": true, // Enable sorting
            "language": {
                "info": "", // Remove "Showing 1 to 10 of 21 results from the bottom"
            }
        });
        })
        function deleteb(id){
            if (confirm('Are you sure you want to delete?')) {
                $.ajax({
                    url: '{{ route("books.destroy") }}',
                    type: 'DELETE',
                    data: { id: id },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                        window.location.href = '{{ route("books.index") }}';
                },
                    error: function() {
                        window.location.href = '{{ route("books.index") }}';
                    }
                });
            }
        }
    </script>
    @endsection