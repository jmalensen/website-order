@extends('layouts.app')
@section('pagetitle')@lang('customers.index')@endsection

@section('content')
	<div class="block">
		<div class="block-content block-content-full border-left border-3x border-primary">
			@can('create', \App\Models\Customer::class)
				<a href="{{route('customers.create')}}"
				   class="btn btn-hero-sm btn-hero-primary float-right">
					<i class="fa fa-plus"></i> @lang('commun.add')
				</a>
			@endcan

			@can('reallocation', \App\Models\Customer::class)
				<a href="{{route('customers.reallocation')}}"
				   class="btn btn-hero-sm btn-hero-info float-right mr-2">
					<i class="fa fa-edit"></i> @lang('customers.reallocation')
				</a>
			@endcan
				<div class="clearfix mb-2"></div>
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
							<th>@sortablelink('company_name', __("customers.company_name"))</th>
							<th class="text-capitalize">@lang("customers.address")</th>
							<th class="text-capitalize">@lang("customers.address_complement")</th>
							<th class="text-capitalize">@lang("customers.city")</th>
							<th class="text-capitalize">@lang("customers.postcode")</th>
							<th class="text-capitalize">@lang("customers.country")</th>
							<th class="text-capitalize">@lang("customers.phone")</th>
							<th class="text-capitalize">@lang("users.user_appro")</th>
							@can('seeAdminSettings', \App\User::class)
								<th class="text-capitalize">@lang("groupes.groupe")</th>
							@endcan

							<th width="200px;">@lang('commun.actions')</th>
					    </tr>
					</thead>

					<tbody>
					@foreach($customers as $customer)
						<tr>
							<td>
								@can('view', $customer)
									<a href="{{ route('customers.view', [$customer]) }}">
										{{ $customer->company_name }}
									</a>
								@else
									{{ $customer->company_name }}
								@endcan
							</td>
							<td>{{ $customer->address }}</td>
							<td>{{ $customer->address_complement }}</td>
							<td>{{ $customer->city }}</td>
							<td>{{ $customer->postcode }}</td>
							<td>{{ $customer->country }}</td>
							<td>{{ $customer->phone }}</td>
							<td>
								<a href="{{ route('users.view', [ data_get($customer, 'user') ]) }}">
									{{ data_get($customer, 'user.libelle') }}
								</a>
							</td>

							@can('seeAdminSettings', \App\User::class)
								<td>{{ data_get($customer, 'groupe.name') }}</td>
							@endcan

							<td>
								@can('view', $customer)
									<a title="@lang('commun.view')"
									   href="{{ route('customers.view', ['customer' => $customer->id]) }}"
									   class="btn btn-hero-sm btn-hero-info" data-toggle="tooltip">
										<i class="fa fa-eye"></i>
									</a>
								@endcan
								@can('edit', $customer)
									<a title="@lang('commun.edit')"
									   href="{{ route('customers.edit', ['customer' => $customer->id]) }}"
									   class="btn btn-hero-sm btn-hero-primary" data-toggle="tooltip">
										<i class="fa fa-edit"></i>
									</a>
								@endcan
								@can('delete', $customer)
									<a title="@lang('commun.delete')"
									   href="{{ route('customers.delete', ['customer' => $customer->id]) }}" data-delete=""
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
				{{ $customers->appends(\Request::except('page'))->render() }}
		</div>
	</div>

@endsection