@extends('layouts.app')
@section('pagetitle')@lang('groupes.index')@endsection

@section('content')
	<div class="block">
		<div class="block-content block-content-full border-left border-3x border-primary">
			@can('create', \App\Models\Groupe::class)
				<a href="{{route('groupes.create')}}"
				   class="btn btn-hero-sm btn-hero-info float-right">
					<i class="fa fa-plus"></i> @lang('commun.add')
				</a>
				<div class="clearfix mb-2"></div>
			@endcan
			<div class="row">
				<div class="col-md-12">
					{{Form::open(['method' => 'GET'])}}
					{{Form::considerRequest(true)}}
					{{Form::searchGroup('search')}}
					{{Form::close()}}
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead class="thead-light">
					    <tr>
					<th class="text-capitalize">@lang("groupes.name")</th> 

					    <th width="200px;"></th>
					    </tr>
					</thead>

					<tbody>
					@foreach($groupes as $groupe)
						<tr>
						<td>{{ $groupe->name }}</td> 

							<td>
								@can('edit', $groupe)
									<a title="@lang('commun.edit')"
									   href="{{ route('groupes.edit', ['groupe' => $groupe->id]) }}"
									   class="btn btn-hero-sm btn-hero-primary" data-toggle="tooltip">
										<i class="fa fa-edit"></i>
									</a>
								@endcan
								@can('delete', $groupe)
									<a title="@lang('commun.delete')"
									   href="{{ route('groupes.delete', ['groupe' => $groupe->id]) }}" data-delete=""
									   data-message="" class="btn btn-hero-sm btn-hero-danger" data-toggle="tooltip">
										<i class="fa fa-trash"></i>
									</a>
								@endcan
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
				{{ $groupes->appends(\Request::except('page'))->render() }}
		</div>
	</div>

@endsection