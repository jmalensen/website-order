<div class="row" id="myvueorder">

	@include('layouts.orders.searchproducts')

	<div class="col-12 col-md-5 col-lg-4">
		@if(!empty($order->id))
			{{ Form::model($order, ['route' => ['member.orders.update', $order->id], 'method' => 'POST', 'id' => 'order-form']) }}
			{{Form::hidden('id', null, ['value' => $order->id])}}
		@else
			{{ Form::model($order, ['route' => 'member.orders.store', 'id' => 'order-form']) }}
		@endif
			<div class="block border-left border-3x border-primary">
				<div class="block-content  block-content-full">
					<div class="form-group">
						{{ Form::label('customerChoice', __('customers.customer')) }}
						{!! Form::select('customer_id', $customers, null, ['id' => 'customerChoice']) !!}
					</div>

					<h3>@lang('products.amount')</h3>

					@include('layouts.orders.listaddedproducts')

					<p class="text-danger font-weight-bold">
						@lang('orders.info_edit')
					</p>
				</div>

				<div class="block-content block-content-full block-content-sm bg-body-light text-right">
					{{ Form::cancelButton(route('member.orders.index')) }}
					{{ Form::submitButton() }}
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>

@include('layouts.orders.scriptproducts')