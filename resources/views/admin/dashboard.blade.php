@extends('layouts.dashboard-layout')

@section('title')
<title>Hackathon - Dashboard</title>
@endsection

@section('actions')
<div class="col-md-4 offset-1 d-grid gap-2">
    <a href="#" class="btn btn-success shadow" data-bs-toggle="modal" data-bs-target="#createCompModal">
        <i class="fas fa-plus"></i> Create Competition
    </a>
</div>
<div class="col-md-4 offset-1">
    <input type="text" name="search" id="search" class="form-control shadow" placeholder="Search">
</div>
@endsection

@section('customised-msg')
{{-- Success msg after creation --}}
<div class="alert alert-success alert-dismissible fade show" role="alert" id="success-msg" style="display: none;">
    <strong>Competition created successfully</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

{{-- Success msg after edition --}}
@if (session()->has('competition_edited'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('competition_edited') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Success msg after deletion --}}
@if (session()->has('competition_deleted'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('competition_deleted') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Success msg after profile edition --}}
@if (session()->has('profile_edited'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('profile_edited') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@endsection

@section('content')
@foreach ($competitions as $competition)
<div class="col-lg-4 col-sm-6 mb-4">
    <div class="card h-80 shadow-sm">
        <div class="card-header">
            <h4>{{$competition->name}}</h4>
        </div>
        <div class="card-body">
            <p class="card-text">From : {{$competition->start_date}}</p>
            <p class="card-text">To : {{$competition->end_date}}</p>
            <table>
                <tr>
                    <td>
                        <input type="hidden" name="competition-id" id="competition-id" value="{{$competition->id}}">
                    </td>
                    <td>
                        <a href="{{ route('competitions.teams.index',$competition->id) }}" class="btn btn-primary">Visit</a>
                    </td>
                    <td>
                        <a href="{{route('competitions.edit',$competition->id)}}" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger delete-comp" data-bs-toggle="modal" data-bs-target="#deleteCompetitionModal">Delete</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('pagination')
    
@endsection

@section('customised-modal')
<div class="modal fade" id="createCompModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Competition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create_competition_form" method="POST" action="{{ route('competitions.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="comp_name">Name</label>
                        <input type="text" name="name" class="form-control" id="comp_name" value="{{ old('name') }}">
                        <span class="invalid-feedback">
                            <strong id="name_error"></strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        <span class="invalid-feedback">
                            <strong id="start_date_error"></strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                        <span class="invalid-feedback">
                            <strong id="end_date_error"></strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button name="create" class="btn btn-success" id="create" type="submit">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCompetitionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Competition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete this competition? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark" data-bs-dismiss="modal">Back</button>
                <form id="delete-competition-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button name="delete" class="btn btn-danger" id="delete" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customised-js')
<script type="text/javascript">
    function deleteCompetition() {
        var tr = this.parentElement.parentElement;
        var competition_id = tr.children[0].children[0].value;

        //Setting up the action for the delete form 
        document.getElementById("delete-competition-form").action = "/competitions/"+competition_id;
    }

    function clickOnDelete(){
        var deleteButtons = document.getElementsByClassName("delete-comp");
        for (let i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].addEventListener("click",deleteCompetition);
        }
    }

    $(document).ready(function(){

        clickOnDelete();

        if(localStorage.getItem("success")){
            $('#success-msg').css('display', 'block')
            localStorage.clear();
        }

        $('#create_competition_form').on('submit', function(e){
            e.preventDefault();
            $('#name_error').html("");
            $('#comp_name').removeClass('is-invalid');
            $('#start_date_error').html("");
            $('#start_date').removeClass('is-invalid');
            $('#end_date_error').html("");
            $('#end_date').removeClass('is-invalid');
            $.ajax({
                type:'POST',
                url:'/competitions',
                data:$('#create_competition_form').serialize(),
                dataType: 'json',
                success:function(data){
                    if(data.errors) {
                        if(data.errors.name){
                            $('#name_error').html(data.errors.name[0]);
                            $('#comp_name').addClass('is-invalid');
                        }
                        if(data.errors.start_date){
                            $('#start_date_error').html(data.errors.start_date[0]);
                            $('#start_date').addClass('is-invalid');
                        }
                        if(data.errors.end_date){
                            $('#end_date_error').html(data.errors.end_date[0]);
                            $('#end_date').addClass('is-invalid');
                        }
                    }
                    if(data.success) {
                        $('#createCompModal').modal('hide');
                        localStorage.setItem("success",data.OperationStatus)
                        window.location.reload();
                    }
                },
            })
        })
        //alert(1);
        /*var archiveButtons = document.getElementsByClassName('archive-class')
        for (let i = 0; i < archiveButtons.length; i++) {
            archiveButtons[i].addEventListener('click',archiveClass); 
        }*/
    })
</script>    
@endsection

</body>
</html>