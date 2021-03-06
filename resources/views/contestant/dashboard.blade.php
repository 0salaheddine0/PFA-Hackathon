@extends('layouts.dashboard-layout')

@section('title')
    <title>Hackathon - Dashboard</title>
@endsection

<!--ACTION SECTION -->
@section('actions')
<div class="col-md-2 d-grid offset-1">
    <a href="#" class="btn btn-warning btn-block shadow" data-bs-toggle="modal" data-bs-target="#joinHackathonModal">
        <i class="fas fa-plus"></i> Join Hackathon
    </a>
</div>
<div class="col-md-2 d-grid">
    <a href="#" class="btn btn-success btn-block shadow" data-bs-toggle="modal" data-bs-target="#createTeamModal">
        <i class="fas fa-plus"></i> Create Team
    </a>
</div>
<div class="col-md-4 offset-2">
    <form method="GET" action="">
        <div class="input-group">
            <input type="text" name="search" id="search" class="form-control shadow-sm" placeholder="Search">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </span>
        </div>
    </form>
</div>
@endsection

@section('customised-modal')
    <!-- JOIN HACKATHON MODAL -->
    <div class="modal fade " id="joinHackathonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Join Hackathon</h5>
                    <button class="close" data-bs-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="join_competition_form" method="POST" action={{ route('joinCompetition') }}>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="alert alert-danger mb-2" style="display: none" id="exist-msg">
                                <strong id="exist_error"></strong>
                            </div>
                            <label for="team_name" class="mb-2">Enter team name</label>
                            <input type="text" class="form-control" id="team_name" name="name">
                            <span class="invalid-feedback mb-2">
                                <strong id="name_error"></strong>
                            </span>

                            <label for="class_code" class="mb-2">Enter hackathon code to join</label>
                            <input type="text" class="form-control" id="comp_code" name="code">
                            <span class="invalid-feedback mb-2">
                                <strong id="code_error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" id="join" type="submit">Join</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ./JOIN CLASS MODAL -->

    <!-- CREATE TEAM MODAL -->
    <div class="modal fade" id="createTeamModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Create Team</h5>
                    <button class="close" data-bs-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="create_team_form" method="POST" action="{{route('team.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="team_name" class="mb-2">Team name</label>
                            <input type="text" class="form-control" id="team_name" name="name">
                            
                            <label for="num_members" class="mb-2">Number of members</label>
                            <input type="number" class="form-control mb-2" id="members" min="1" value="1" name="num_members">

                            <div class="team_member mb-2">
                                <label for="team_member_1" class="mb-2">Team Leader</label>
                                <input type="email" class="form-control" id="team_member_1" name="member1" placeholder={{Auth::guard(get_guard())->user()->email}} value={{Auth::guard(get_guard())->user()->email}} readonly>
                            </div>


                            {{-- 
                            <div class="team_member mb_1">
                                <label for="team_member_1" class="mb-2">Team member 1</label>
                                <input type="text" class="form-control" id="team_member_1" name="member1">
                            </div>
                            --}}
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" id="join" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- CREATE HACKATHON MODAL END -->
@endsection

@section('customised-msg')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{session('status')}}</strong>
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Success msg after profile edition --}}
@if (session()->has('profile_edited'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('profile_edited') }}</strong>
    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@endsection

@section('content')
    @foreach ($competitions as $team_id => $competition)
        @foreach ($competition as $team_competition)
        <div class="col-lg-4 col-sm-6 mb-4">
            <div class="card h-80 shadow-sm">
                <div class="card-header">
                    <h4>{{$team_competition->name}}</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Team : {{App\Models\Team::where('id',$team_id)->first()->name}}</p>
                    <p class="card-text">From : {{$team_competition->start_date}}</p>
                    <p class="card-text">To : {{$team_competition->end_date}}</p>
                    <p class="card-text">Hackathon will be available in : 
                        {{\Carbon\Carbon::parse($team_competition->start_date)->diffInDays(\Carbon\Carbon::parse(\Carbon\Carbon::now()))}} days
                    </p>
                    <a href={{route('competitions.teams.projects.index',[$team_competition->id,$team_id])}} class="btn btn-primary 
                        @if(\Carbon\Carbon::parse($team_competition->start_date)->diffInDays(\Carbon\Carbon::parse(\Carbon\Carbon::now()))!==0) disabled @endif">Enter</a>
                    <a href="#" class="btn btn-danger">Exit</a>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
@endsection 

@section('customised-js')
<script type="text/javascript">
        $(document).ready(function(){
            $('#join_competition_form').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    type:'POST',
                    url:'/join',
                    data:$('#join_competition_form').serialize(),
                    dataType: 'json',
                    success:function(data){
                        if(data.errors) {
                            if(data.errors.name){
                                $('#name_error').html(data.errors.name[0]);
                                $('#team_name').addClass('is-invalid');
                            }
                            if(data.errors.code){
                                $('#code_error').html(data.errors.code[0]);
                                $('#comp_code').addClass('is-invalid');
                            }
                        }
                        if(data.success) {
                            $('#joinCompModal').modal('hide');
                            localStorage.setItem("success",data.OperationStatus)
                            window.location.reload();
                        }
                        if(data.exist){
                            $('#exist_error').html("Hackathon already Joined");
                            $('#exist-msg').css('display', 'block');
                            $('#team_name').val("");
                            $('#comp_code').val("");
                        }
                    },
                })
            })
        })

        function createNewInput(n){
            const div = document.createElement('div'); //team_member
            div.setAttribute('class','team_member mb-2');
            //child.classList.add(' ');
            const label = document.createElement('label');
            label.setAttribute('for',`team_member_${n}`);
            label.setAttribute('class','mb-2');
            label.textContent=`Team Member ${n}`;

            const input = document.createElement('input');
            input.setAttribute('type','email');
            input.setAttribute('class','form-control');
            input.setAttribute('id',`team_member_${n}`);
            input.setAttribute('name',`member${n}`);
            input.setAttribute('placeholder',`Enter email adress`);

            div.appendChild(label);
            div.appendChild(input);
            form.appendChild(div);
        }
        function deleteLastInput(){
            const form = document.querySelector('#createTeamModal .form-group');
            form.removeChild(form.lastChild);
        }
        const form = document.querySelector('#createTeamModal .form-group');
        const numberOfMembers = document.querySelector('#members');
        let old = numberOfMembers.value;
        numberOfMembers.addEventListener('change', ()=>{
            let n = numberOfMembers.value;
            if(n>old) createNewInput(n);
            else deleteLastInput();
            old = n;
        });

        


</script>
@endsection
