@if(!empty($product->id))
	{{ Form::model($product, ['route' => ['products.update', $product->id], 'method' => 'post']) }}
	{{Form::hidden('id', null, ['value' => $product->id])}}
@else
	{{ Form::model($product, ['route' => 'products.store']) }}
@endif
<div class="row" id="myvue">
	<div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">

		<div class="block border-left border-3x border-primary">
			<div class="block-content  block-content-full">
				{{Form::textGroup('code', __('products.code'))}}
 
				{{Form::textGroup('name', __('products.name'))}}
 
				{{Form::textareaGroup('description', __('products.description'))}}
 
				{{Form::amountGroup('price_ht', __('products.price_ht'))}}

				{{Form::label(__('products.rate_vat') ) }} : 5.50%

				{{Form::input('hidden', 'rate_vat', '5.50')}}

				@include('layouts.inc.groupechoice')
			</div>

			<div class="block-content block-content-full block-content-sm bg-body-light text-right">
				{{ Form::cancelButton(route('products.index')) }}
				{{ Form::submitButton() }}
			</div>
		</div>
	</div>
</div>
{{ Form::close() }}