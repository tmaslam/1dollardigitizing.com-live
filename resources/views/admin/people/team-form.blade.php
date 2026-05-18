@extends('layouts.admin')

@php
    $currentRole = old('role', match(true) {
        $accountType === 'supervisor'                      => 'supervisor',
        ($team->team_group ?? '') === 'freelance'          => 'freelance',
        default                                            => 'inhouse',
    });
    $label = match($currentRole) {
        'supervisor' => 'Supervisor',
        'freelance'  => 'Freelance',
        default      => 'In-House',
    };
@endphp

@section('title', ($mode === 'create' ? 'Create '.$label : 'Edit '.$label).' | 1Dollar Admin')
@section('page_heading', $mode === 'create' ? 'Create '.$label.' Account' : 'Edit Account #'.$team->user_id)
@section('page_subheading', 'Set the role, login credentials, and contact details for this team account.')

@section('content')
    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <section class="card">
        <div class="card-body">
            <div class="section-head">
                <div>
                    <h3>{{ $mode === 'create' ? 'Create Account' : 'Edit Account' }}</h3>
                    <p class="section-copy">Save login and contact details for this team account.</p>
                </div>
                <a class="badge" href="{{ url('/v/show-all-teams.php') }}">Back to Teams</a>
            </div>
            <form method="post" action="{{ url('/v/create-teams.php') }}" class="toolbar">
                @csrf
                @if ($team->exists)
                    <input type="hidden" name="user_id" value="{{ $team->user_id }}">
                @endif

                <div class="field" style="min-width:100%;">
                    <label>Account Role</label>
                    <div style="display:flex;gap:24px;align-items:center;padding:10px 0;flex-wrap:wrap;">
                        <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                            <input type="radio" name="role" value="supervisor" @checked($currentRole === 'supervisor') style="width:auto;min-height:auto;" onchange="updateNameLabel(this.value)">
                            Supervisor
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                            <input type="radio" name="role" value="inhouse" @checked($currentRole === 'inhouse') style="width:auto;min-height:auto;" onchange="updateNameLabel(this.value)">
                            In-House Team
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                            <input type="radio" name="role" value="freelance" @checked($currentRole === 'freelance') style="width:auto;min-height:auto;" onchange="updateNameLabel(this.value)">
                            Freelance Team
                        </label>
                    </div>
                </div>

                <div class="field">
                    <label for="txtTeamName" id="name-label">{{ $currentRole === 'supervisor' ? 'Supervisor Name' : 'Team Name' }}</label>
                    <input id="txtTeamName" type="text" name="txtTeamName" value="{{ old('txtTeamName', $team->user_name) }}">
                </div>
                <div class="field">
                    <label for="txtPassword">Password</label>
                    <input id="txtPassword" type="password" name="txtPassword" value="{{ old('txtPassword') }}" autocomplete="new-password" placeholder="{{ $mode === 'create' ? 'Create a password' : 'Leave blank to keep current password' }}">
                </div>
                <div class="field">
                    <label for="txtCPassword">Confirm Password</label>
                    <input id="txtCPassword" type="password" name="txtCPassword" value="{{ old('txtCPassword') }}" autocomplete="new-password">
                </div>
                <div class="field">
                    <label for="txtEmail">Email</label>
                    <input id="txtEmail" type="email" name="txtEmail" value="{{ old('txtEmail', $team->user_email) }}">
                </div>
                <div class="field" style="min-width:auto;">
                    <label>&nbsp;</label>
                    <button type="submit">{{ $mode === 'create' ? 'Create Account' : 'Save Account' }}</button>
                </div>
            </form>
        </div>
    </section>

    <script>
    function updateNameLabel(role) {
        document.getElementById('name-label').textContent = role === 'supervisor' ? 'Supervisor Name' : 'Team Name';
    }
    </script>
@endsection
