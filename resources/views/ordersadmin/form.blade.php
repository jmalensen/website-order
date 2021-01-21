<div class="row" id="myvueorder">

    @include('layouts.orders.searchproducts')

    <div class="col-12 col-md-5 col-lg-4">
        @if(!empty($order->id))
            {{ Form::model($order, ['route' => ['admin.orders.update', $order->id], 'method' => 'POST', 'id' => 'order-form']) }}
            {{Form::hidden('id', null, ['value' => $order->id])}}
        @else
            {{ Form::model($order, ['route' => 'admin.orders.store', 'id' => 'order-form']) }}
        @endif

            <div class="block border-left border-3x border-primary">
                <div class="block-content  block-content-full">
                    <div class="form-group">
                        @if(!empty($order->id))
                            @lang('orders.code'): {{$order->code}}
                        @endif
                    </div>

                    <div v-if="listUsers != ''" class="form-group">
                        <label for="userChoice">@lang('users.user_appro')</label>
                        <select id="userChoice"
                                autocomplete="off"
                                name="user_id"
                                class="form-control"
                                v-on:change="changeCustomerEvent($event)"
                                v-model="selectedUser">
                            <option v-for="(user, index) in listUsers" :value="user.id">@{{ user.name }}</option>
                        </select>
                    </div>

                    <div v-if="listCustomers != ''" class="form-group">
                        <label for="customerChoice">@lang('customers.customer')</label>
                        <select id="customerChoice"
                                autocomplete="off"
                                name="customer_id"
                                class="form-control"
                                v-model="selectedCustomer">
                            <option v-for="(customer, index) in listCustomers" :value="customer.id">@{{ customer.company_name }}</option>
                        </select>
                    </div>

                    @can('seeAdminSettings', \App\User::class)
                        {{Form::openGroup('date_entered', __('orders.date_entered'))}}
                        {{Form::datetimepicker('date_entered')}}
                        <p class="m-0 text-danger">
                            @lang('orders.info_date')
                        </p>
                        {{Form::closeGroup()}}
                    @endcan


                    <h3>@lang('products.amount')</h3>

                    @include('layouts.orders.listaddedproducts')

                </div>

                <div class="block-content block-content-full block-content-sm bg-body-light text-right">
                    {{ Form::cancelButton(route('admin.orders.index')) }}
                    {{ Form::submitButton() }}
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>

@include('layouts.orders.scriptproducts')