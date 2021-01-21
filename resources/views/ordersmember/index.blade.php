@extends('layouts.app')
@section('pagetitle')@lang('orders.index')@endsection

@section('content')
	<div class="block">
		<div class="block-content block-content-full border-left border-3x border-primary">
			<span class="font-weight-bold text-danger">Toute commande doit être saisie avant
			{{ Carbon\Carbon::createFromTimeString(config('app.lastTimeToOrder'))->format('H\hi')}}.
			 Il n'est pas possible de saisir des commandes après {{ Carbon\Carbon::createFromTimeString(config('app.lastTimeToOrder'))->format('H\hi')}}.</span>

			@can('createMember', \App\Models\Order::class)
				@if($canDoOrder)
					<a href="{{route('member.orders.create')}}"
					   class="btn btn-hero-sm btn-hero-primary float-right">
						<i class="fa fa-plus"></i> @lang('commun.add')
					</a>
					<div class="clearfix mb-2"></div>
				@endif
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
							<th class="text-capitalize">@lang("orders.code")</th>
							<th>@sortablelink('date_entered', __("orders.date_entered"))</th>
							<th>@sortablelink('date_closed', __("orders.date_closed"))</th>
							<th class="text-capitalize">@lang("customers.company_name")</th>
							<th class="text-capitalize">@lang("orders.status")</th>

							<th width="200px;">@lang('commun.actions')</th>
					    </tr>
					</thead>

					<tbody>
					@foreach($orders as $order)
						<tr>
							<td>{{ $order->code }}</td>
							<td>{{ Helpers::datetime($order->date_entered) }}</td>
							<td>{{ Helpers::datetime($order->date_closed) }}</td>
							<td>
								{{ data_get($order, 'customer.company_name') }}
							</td>
							<td>
								{!! $order->getStatusHTML() !!}

								@if($order->canPutLosses())
									<a title="@lang('orders.loss_can_be_entered')"
									   href="{{ route('member.orders.view', ['order' => $order->id]) }}"
									   class="text-danger" data-toggle="tooltip">
										<i class="fa fa-exclamation-circle"></i>
									</a>
								@endif
							</td>

							<td>
								@can('viewMember', $order)
									<a title="@lang('commun.view')"
									   href="{{ route('member.orders.view', ['order' => $order->id]) }}"
									   class="btn btn-hero-sm btn-hero-info" data-toggle="tooltip">
										<i class="fa fa-eye"></i>
									</a>
								@endcan
								@can('editMember', $order)
									<a title="@lang('commun.edit')"
									   href="{{ route('member.orders.edit', ['order' => $order->id]) }}"
									   class="btn btn-hero-sm btn-hero-primary" data-toggle="tooltip">
										<i class="fa fa-edit"></i>
									</a>
								@endcan
								@can('duplicateMember', $order)
									<a title="@lang('orders.duplicate')"
									   href="#"
									   data-id="{{$order->id}}"
									   class="duplicate btn btn-hero-sm btn-hero-warning" data-toggle="tooltip">
										<i class="fa fa-copy"></i>
									</a>
									<form id="duplicate{{$order->id}}" method="POST" action="{{ route('member.orders.duplicate', ['order' => $order->id]) }}">
										{{ csrf_field() }}
									</form>
								@endcan

								@can('deleteMember', $order)
									<a title="@lang('commun.delete')"
									   href="{{ route('member.orders.delete', ['order' => $order->id]) }}" data-delete=""
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
				{{ $orders->appends(\Request::except('page'))->render() }}
		</div>
	</div>

@endsection

@push('js_after')
	<script type="text/javascript">
		$(document).ready(function() {

			$('.duplicate').on('click', function(e){
				e.preventDefault();
				$(this).next().submit();
			});
		});
	</script>
@endpush