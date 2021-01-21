@extends('layouts.app')
@section('pagetitle')@lang('customers.reallocation')@endsection

@section('content')
	<div class="block">
		<div class="block-content block-content-full border-left border-3x border-primary">
			<a href="{{route('customers.index')}}"
			   class="btn btn-light btn-sm btn-rounded px-3">
				<i class="fa fa-arrow-circle-left mr-1"></i> @lang('commun.back')
			</a>
				<div class="clearfix mb-2"></div>

			{{ Form::open(['method' => 'POST', 'route' => 'customers.storerealloc']) }}
			<div class="table-responsive">
				<table class="table js-table-checkable table-striped table-hover">
					<thead class="thead-dark">
					    <tr>
							<th class="text-capitalize">
								<div class="custom-control custom-checkbox custom-control-primary d-inline-block">
									<input type="checkbox" class="custom-control-input" id="check-all" name="check-all"/>
									<label class="custom-control-label" for="check-all"></label>
								</div>
							</th>
							<th class="text-capitalize">@lang("customers.company_name")</th>
							<th class="text-capitalize">@lang("customers.address")</th>
							<th class="text-capitalize">@lang("customers.address_complement")</th>
							<th class="text-capitalize">@lang("customers.city")</th>
							<th class="text-capitalize">@lang("customers.postcode")</th>
							<th class="text-capitalize">@lang("customers.country")</th>
							<th class="text-capitalize">@lang("customers.phone")</th>
							<th class="text-capitalize">@lang("users.user_appro")</th>

{{--							<th width="200px;">@lang('commun.actions')</th>--}}
					    </tr>
					</thead>

					<tbody>
					@foreach($customers as $customer)
						<tr>
							<td class="text-center">
								<div class="custom-control custom-checkbox custom-control-primary d-inline-block">
									<input type="checkbox" class="custom-control-input" id="row_{{$customer->id}}" name="customer[]" value="{{ $customer->id }}" />
									<label class="custom-control-label" for="row_{{$customer->id}}"></label>
								</div>
							</td>
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
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>

			<div class="block-content block-content-full block-content-sm bg-body-light text-right">

				<div class="offset-8 col-4">
					{{ Form::openGroup('user_id', __('users.user_appro')) }}
					{{ Form::select2('user_id', $users, null, ['id' => 'user_id']) }}
					{{ Form::closeGroup() }}
				</div>

				{{ Form::cancelButton(route('customers.index')) }}
				{{ Form::submitButton() }}
			</div>
			{{ Form::close() }}

			{{ $customers->appends(\Request::except('page'))->render() }}
		</div>
	</div>

@endsection

@push('js_after')
	<script type="text/javascript">
		$(document).ready(function() {
			Dashmix.helpers(['table-tools-checkable']);
		});
	</script>
@endpush