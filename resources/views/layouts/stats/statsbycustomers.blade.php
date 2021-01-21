<div class="row">
    <div class="block col-md-12 pt-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="font-w300 mb-0 mr-1">@lang('commun.stats_bycustomer')</h2>
            <div class="buttons">
                <a title="@lang('commun.export_stats_bycustomer')"
                   href="{{ route('stats.exportcustomerxls', ['currentDay' => $currentDay, 'dateEnd' => $dateEnd]) }}"
                   data-id="bc"
                   class="export btn btn-hero-sm btn-secondary btn-sm px-3" data-toggle="tooltip">
                    <i class="fa fa-file-excel mr-1"></i> @lang('commun.export_xls')
                </a>
                <a title="@lang('commun.export_stats_bycustomer')"
                   href="{{ route('stats.exportcustomer', ['currentDay' => $currentDay, 'dateEnd' => $dateEnd]) }}"
                   data-id="bc"
                   class="export btn btn-hero-sm btn-secondary btn-sm px-3" data-toggle="tooltip">
                    <i class="fa fa-file-pdf mr-1"></i> @lang('commun.export_pdf')
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                <tr>
                    <th class="text-capitalize">@lang("customers.company_name")</th>
                    <th class="text-capitalize">@lang("customers.nb_orders")</th>

                    @can('seeAdminSettings', \App\User::class)
                        <th class="text-capitalize">@lang("customers.total_price_orders")</th>
                    @endcan
                </tr>
                </thead>

                <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td>
                            <a href="{{ route('customers.view', [$customer->customer]) }}">
                                {{ $customer->customer->company_name }}
                            </a>
                        </td>
                        <td>
                            {{ $customer->countOrder }}
                        </td>
                        @can('seeAdminSettings', \App\User::class)
                            <td class='text-right'>{{ Helpers::amount($customer->total_price) }}</td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>