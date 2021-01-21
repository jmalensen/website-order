@if(!empty($customer->id))
	{{ Form::model($customer, ['route' => ['customers.update', $customer->id], 'method' => 'post']) }}
	{{Form::hidden('id', null, ['value' => $customer->id])}}
@else
	{{ Form::model($customer, ['route' => 'customers.store']) }}
@endif
<div class="row" id="myvue">
	<div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">

		<div class="block border-left border-3x border-primary">
			<div class="block-content  block-content-full">
				{{Form::textGroup('company_name', __('customers.company_name'))}}
 
				{{Form::textGroup('address', __('customers.address'))}}
 
				{{Form::textGroup('address_complement', __('customers.address_complement'))}}

				{{Form::textGroup('postcode', __('customers.postcode'))}}

				{{Form::textGroup('city', __('customers.city'))}}

				{{ Form::openGroup('country', __('customers.country')) }}
				{!! Form::select('country', \App\Models\Customer::$countries, $customer->country, ['id' => 'country']) !!}
				{{ Form::closeGroup() }}

				{{Form::textGroup('phone', __('customers.phone'))}}

				{{ Form::openGroup('user_id', __('users.user_appro_ref')) }}
				{!! Form::select('user_id', $users, null, ['id' => 'user_id']) !!}
				{{ Form::closeGroup() }}

				@include('layouts.inc.groupechoice')
			</div>

			<div class="block-content block-content-full block-content-sm bg-body-light text-right">
				{{ Form::cancelButton(route('customers.index')) }}
				{{ Form::submitButton() }}
			</div>
		</div>
	</div>
</div>
{{ Form::close() }}