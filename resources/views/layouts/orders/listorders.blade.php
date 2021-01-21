<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-light">
        <tr class="text-capitalize">
            <th>@lang("orders.code")</th>
            <th>@sortablelink('date_entered', __("orders.date_entered"))</th>
            <th>@sortablelink('date_closed', __("orders.date_closed"))</th>
            @if( empty($user) )
                <th>@lang("users.user_appro")</th>
            @endif
            <th>@lang("customers.company_name")</th>
            @can('seeAdminSettings', \App\User::class)
                <th>@sortablelink('total_price', __("orders.total"))</th>
                <th>@lang("groupes.groupe")</th>
            @endcan
            <th>@lang("orders.status")</th>

            <th width="340px;">@lang('commun.actions')</th>
        </tr>
        </thead>

        <tbody>
        @foreach($orders as $order)
            <tr>
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

                @if( empty($user) )
                    <td>
                        {!! (!empty($order->user)) ? '<a href="'.route('users.view', [ $order->user ]).'">'. $order->user->getLibelle().'</a>' : '' !!}
                    </td>
                @endif

                <td>
                    <a href="{{ route('customers.view', [ $order->customer ]) }}">
                        {{ data_get($order, 'customer.company_name') }}
                    </a>
                </td>
                @can('seeAdminSettings', \App\User::class)
                    <td class='text-right'>{{Helpers::amount($order->total_price)}}</td>
                    <td>{{ data_get($order, 'groupe.name') }}</td>
                @endcan
                <td>{!! $order->getStatusHTML() !!}</td>

                <td>
                    @can('viewAdmin', $order)
                        <a title="@lang('commun.view')"
                           href="{{ route('admin.orders.view', ['order' => $order->id]) }}"
                           class="btn btn-hero-sm btn-hero-info" data-toggle="tooltip">
                            <i class="fa fa-eye"></i>
                        </a>
                    @endcan
                    @can('editAdmin', $order)
                        <a title="@lang('commun.edit')"
                           href="{{ route('admin.orders.edit', ['order' => $order->id]) }}"
                           class="btn btn-hero-sm btn-hero-primary" data-toggle="tooltip">
                            <i class="fa fa-edit"></i>
                        </a>
                    @endcan

                    @can('closeAdmin', $order)
                        <a title="@lang('orders.close')"
                           href="#"
                           data-id="{{$order->id}}"
                           class="finish btn btn-hero-sm btn-hero-success" data-toggle="tooltip">
                            <i class="fa fa-check"></i>
                        </a>
                        <form id="finish{{$order->id}}" method="POST" action="{{ route('admin.orders.close', ['order' => $order->id]) }}" class="d-none">
                            {{ csrf_field() }}
                        </form>
                    @endcan
                    @can('pauseAdmin', $order)
                        <a title="@lang('orders.problem')"
                           href="#"
                           data-id="{{$order->id}}"
                           class="pause btn btn-hero-sm btn-hero-danger" data-toggle="tooltip">
                            <i class="fa fa-exclamation-circle"></i>
                        </a>
                        <form id="pause{{$order->id}}" method="POST" action="{{ route('admin.orders.pause', ['order' => $order->id]) }}" class="d-none">
                            {{ csrf_field() }}
                        </form>
                    @endcan

                    @can('cancelAdmin', $order)
                        <a title="@lang('orders.cancel')"
                           href="#"
                           data-id="{{$order->id}}"
                           class="cancel btn btn-hero-sm btn-hero-secondary" data-toggle="tooltip">
                            <i class="fa fa-times"></i>
                        </a>
                        <form id="cancel{{$order->id}}" method="POST" action="{{ route('admin.orders.cancel', ['order' => $order->id]) }}" class="d-none">
                            {{ csrf_field() }}
                        </form>
                    @endcan
                    @can('deleteAdmin', $order)
                        <a title="@lang('commun.delete')"
                           href="{{ route('admin.orders.delete', ['order' => $order->id]) }}" data-delete=""
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