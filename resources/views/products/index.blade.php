@extends('layouts.app')
@section('pagetitle')@lang('products.index')@endsection

@section('content')
	<div class="block">
		<div class="block-content block-content-full border-left border-3x border-primary">
			@can('create', \App\Models\Product::class)
				<a href="{{route('products.create')}}"
				   class="btn btn-hero-sm btn-hero-primary float-right">
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
							<th>@sortablelink('code', __("products.code"))</th>
							<th>@sortablelink('name', __("products.name"))</th>
							@can('seeAdminSettings', \App\User::class)
								<th class="text-capitalize">@lang("groupes.groupe")</th>
								<th>@sortablelink('price_ht', __("products.price_ht"))</th>
								<th class="text-capitalize">@lang("products.rate_vat")</th>
							@endcan

							<th width="200px;">@lang('commun.actions')</th>
					    </tr>
					</thead>

					<tbody>
					@foreach($products as $product)
						<tr>
							<td>{{ $product->code }}</td>
							<td>
								@can('view', $product)
									<a href="{{route('products.view', [$product])}}">{{ $product->name }}</a>
								@else
									{{ $product->name }}
								@endcan
							</td>

							@can('seeAdminSettings', \App\User::class)
								<td>{{ data_get($product, 'groupe.name') }}</td>
								<td class='text-right'>{{Helpers::amount($product->price_ht)}}</td>
								<td>{{ $product->rate_vat }}</td>
							@endcan

							<td>
								@can('view', $product)
									<a title="@lang('commun.view')"
									   href="{{ route('products.view', ['product' => $product->id]) }}"
									   class="btn btn-hero-sm btn-hero-info" data-toggle="tooltip">
										<i class="fa fa-eye"></i>
									</a>
								@endcan
								@can('edit', $product)
									<a title="@lang('commun.edit')"
									   href="{{ route('products.edit', ['product' => $product->id]) }}"
									   class="btn btn-hero-sm btn-hero-primary" data-toggle="tooltip">
										<i class="fa fa-edit"></i>
									</a>
								@endcan
								@can('delete', $product)
									<a title="@lang('commun.delete')"
									   href="{{ route('products.delete', ['product' => $product->id]) }}" data-delete=""
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
				{{ $products->appends(\Request::except('page'))->render() }}
		</div>
	</div>

@endsection