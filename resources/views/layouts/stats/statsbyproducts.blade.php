<div class="row">
	<div class="block col-md-12 pt-2">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2 class="font-w300 mb-0 mr-1">@lang('commun.stats_byproduct')</h2>
			<div class="buttons">
				<a title="@lang('commun.export_stats_byproduct')"
				   href="{{ route('stats.exportproductxls', ['currentDay' => $currentDay, 'dateEnd' => $dateEnd]) }}"
				   data-id="bp"
				   class="export btn btn-secondary btn-sm px-3" data-toggle="tooltip">
					<i class="fa fa-file-excel mr-1"></i> @lang('commun.export_xls')
				</a>
				<a title="@lang('commun.export_stats_byproduct')"
				   href="{{ route('stats.exportproduct', ['currentDay' => $currentDay, 'dateEnd' => $dateEnd]) }}"
				   data-id="bp"
				   class="export btn btn-secondary btn-sm px-3" data-toggle="tooltip">
					<i class="fa fa-file-pdf mr-1"></i> @lang('commun.export_pdf')
				</a>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead class="thead-light">
				<tr>
					<th class="text-capitalize">@lang("products.code")</th>
					<th class="text-capitalize">@lang("products.name")</th>
					<th class="text-capitalize">@lang("products.nb_products")</th>

					@can('seeAdminSettings', \App\User::class)
						<th class="text-capitalize">@lang("products.price_ht")</th>
						<th class="text-capitalize">@lang("products.total_price_products")</th>
					@endcan
				</tr>
				</thead>

				<tbody>
				@foreach($productWithOrder as $products)
					@foreach($products as $product)
						<tr>
							<td>{{ $product->product->code }}</td>
							<td>
								<a href="{{ route('products.view', [ $product->product ]) }}">
									{{ $product->product->name }}
								</a>
							</td>
							<td>{{ $product->countProduct }}</td>
							@can('seeAdminSettings', \App\User::class)
								<td class='text-right'>{{Helpers::amount( $product->product->price_ht )}}</td>
								<td class='text-right'>{{Helpers::amount( $product->total_price )}}</td>
							@endcan
						</tr>
					@endforeach
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>