@extends('layouts.app')
@section('pagetitle')@lang('orders.massmodify')@endsection

@section('content')
	<div class="block">
		<div class="block-content block-content-full border-left border-3x border-primary">
			<a href="{{route('admin.orders.index')}}"
			   class="btn btn-light btn-sm btn-rounded px-3">
				<i class="fa fa-arrow-circle-left mr-1"></i> @lang('commun.back')
			</a>
				<div class="clearfix mb-2"></div>

			{{ Form::open(['method' => 'POST', 'route' => 'admin.orders.storemassmodification']) }}
			<div class="table-responsive">
				<table class="table js-table-checkable table-striped table-hover">
					<thead class="thead-dark">
					<tr class="text-capitalize">
						<th class="text-capitalize">
							<div class="custom-control custom-checkbox custom-control-primary d-inline-block">
								<input type="checkbox" class="custom-control-input" id="check-all" name="check-all"/>
								<label class="custom-control-label" for="check-all"></label>
							</div>
						</th>
						<th>@lang("orders.code")</th>
						<th>@sortablelink('date_entered', __("orders.date_entered"))</th>
						<th>@sortablelink('date_closed', __("orders.date_closed"))</th>
						<th>@lang("users.user_appro")</th>
						<th>@lang("customers.company_name")</th>
						@can('seeAdminSettings', \App\User::class)
							<th>@sortablelink('total', __("orders.total"))</th>
						@endcan
						<th>@lang("orders.status")</th>
					</tr>
					</thead>

					<tbody>
					@foreach($orders as $order)
						<tr>
							<td class="text-center">
								<div class="custom-control custom-checkbox custom-control-primary d-inline-block">
									<input type="checkbox" class="custom-control-input" id="row_{{$order->id}}" name="order[]" value="{{ $order->id }}" />
									<label class="custom-control-label" for="row_{{$order->id}}"></label>
								</div>
							</td>
							<td>
								@can('viewAdmin', $order)
									<a href="{{ route('admin.orders.view', ['order' => $order->id]) }}">
										{{ $order->code }}
									</a>
								@else
									{{ $order->code }}
								@endcan
							</td>
							<td>{{ Helpers::datetime($order->date_entered) }}</td>
							<td>{{ Helpers::datetime($order->date_closed) }}</td>
							<td>
								@if( !empty($user) )
									{{$user->getLibelle()}}
								@else
									{!! (!empty($order->user)) ? '<a href="'.route('users.view', [ $order->user ]).'">'. $order->user->getLibelle().'</a>' : '' !!}
								@endif
							</td>
							<td>
								<a href="{{ route('customers.view', [ $order->customer ]) }}">
									{{ data_get($order, 'customer.company_name') }}
								</a>
							</td>
							@can('seeAdminSettings', \App\User::class)
								<td class='text-right'>{{Helpers::amount($order->total_price)}}</td>
							@endcan
							<td>{!! $order->getStatusHTML() !!}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>

			<div class="block-content block-content-full block-content-sm bg-body-light text-right">

				<div class="offset-8 col-4">
					{{ Form::openGroup('new_status', __('orders.new_status')) }}
					{{ Form::select('new_status', $actions, null, ['id' => 'new_status']) }}
					{{ Form::closeGroup() }}
				</div>

				{{ Form::cancelButton(route('admin.orders.index')) }}
				{{ Form::submitButton() }}
			</div>
			{{ Form::close() }}

			{{ $orders->appends(\Request::except('page'))->render() }}
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