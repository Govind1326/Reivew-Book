@if (Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa fa-check-circle" aria-hidden="true"></i>
    {{Session::get('success')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif (Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
    {{Session::get('error')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif (Session::has('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
    {{Session::get('warning')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif