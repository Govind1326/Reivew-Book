@extends('layouts.app')
@section('title')
My Reviews
@endsection
@section('main')   
    <div class="container">
        <div class="row my-5">
            @include('layouts.sidebar')
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        My Reviews
                    </div>
                    <div class="card-body pb-0">
                        <table class="table table-striped mt-3">
                            <thead class="table-primary">
                                <tr>
                                    <th>Book</th>
                                    <th>Review</th>
                                    <th>Rating</th>
                                    <th>Created at</th>
                                    <th>Status</th>                                  
                                    <th>Action</th>
                                </tr>
                                <tbody>
                                    @if ($allreviews->isNotEmpty())
                                    @foreach ($allreviews as $review)
                                    <tr>
                                        <td>{{$review->book->title}}</td>
                                        <td><strong>{{$review->user->name}}:</strong> <br>{{$review->review}}</td>                                        
                                        <td><i class="fa-regular fa-star"></i> {{$review->rating}}</td>
                                        
                                        <td>{{$review->created_at->format('M j, Y')}}</td>
                                        {{-- <td class="{{$review->status?'text-success':'text-danger'}}" >{{$review->status?'Active':'Inactive'}}</td> --}}
                                        <form action="" method="post" id="review_form_{{ $review->id }}">
                                            @csrf
                                            <input type="hidden" name="id" name="id" value="{{$review->id}}">
                                        <td width="20">
                                            <select name="status" class="status-select form-control {{$review->status?'text-success':'text-danger'}}" {{Auth::user()->role=='admin'?'':'disabled'}} id="status-select" data-review-id="{{ $review->id }}">
                                                <option value="1" class="text-success" {{ $review->status ? 'selected' : '' }}>✅</option>
                                                <option value="0" class="text-danger" {{ !$review->status ? 'selected' : '' }}>❌</option>
                                            </select>
                                        </td>
                                        </form>
                                        <td align="center">
                                            <a onclick="deleter({{$review->id}})" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <td colspan="6">Reviews not found.</td>
                                    @endif
                                </tbody>
                            </thead>
                        </table>   
                        <nav aria-label="Page navigation " >
                            {{$allreviews->links()}}
                          </nav>                  
                    </div>
                    
                </div>                
            </div>
        </div>       
    </div>
    @endsection
    @section('script')
    <script>
        $(document).ready(function(){
            $('.status-select').on('change', function () {
                var reviewId = $(this).data('review-id');
                var form = $('#review_form_' + reviewId);
            $.ajax({
                url: '{{ route("myreviews.update") }}',
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.href = '{{ route("myreviews.index") }}';
                },
                error: function(response) {
                    window.location.href = '{{ route("myreviews.index") }}';
                }
            });
        });
    });
            function deleter(id){
                if (confirm('Are you sure you want to delete?')) {
                    $.ajax({
                        url: '{{ route("myreviews.destroy") }}',
                        type: 'DELETE',
                        data: { id: id },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            window.location.href = '{{ route("myreviews.index") }}';
                        },
                        error: function() {
                            window.location.href = '{{ route("myreviews.index") }}';
                        }
                    });
                }
            }
    </script>
    @endsection