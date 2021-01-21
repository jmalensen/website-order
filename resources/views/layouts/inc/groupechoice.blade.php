@can('seeAdminSettings', \App\User::class)
    {{ Form::openGroup('groupe_id', __('groupes.groupe')) }}
    {!! Form::select2('groupe_id', $groupes, null, ['id' => 'groupe_id']) !!}
    {{ Form::closeGroup() }}

    @if(!empty($user))
        {{ Form::checkGroup('no_group', __('groupes.no_group')) }}
    @endif
@endcan