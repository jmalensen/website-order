@if(!empty($groupe->id))
	{{ Form::model($groupe, ['route' => ['groupes.update', $groupe->id], 'method' => 'post']) }}
	{{Form::hidden('id', null, ['value' => $groupe->id])}}
@else
	{{ Form::model($groupe, ['route' => 'groupes.store']) }}
@endif
<div class="row" id="myvue">
	<div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">

		<div class="block border-left border-3x border-primary">
			<div class="block-content  block-content-full">
				{{Form::textGroup('name', __('groupes.name'))}}
 

			</div>

			<div class="block-content block-content-full block-content-sm bg-body-light text-right">
				{{ Form::cancelButton(route('groupes.index')) }}
				{{ Form::submitButton() }}
			</div>
		</div>
	</div>
</div>
{{ Form::close() }}