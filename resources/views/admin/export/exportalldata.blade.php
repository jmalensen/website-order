<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $title }}</title>
</head>
<body>
<h1>{{ $heading }}</h1>

<table width="100%" style="width:100%;margin-bottom:40px;" border="0">
    <thead>
        <tr>
            <th width="20%">@lang("customers.company_name")</th>
            <th width="40%">@lang("customers.nb_orders")</th>

            @can('seeAdminSettings', \App\User::class)
                <th width="40%">@lang("customers.total_price_orders")</th>
            @endcan
        </tr>
    </thead>

    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->customer->company_name }}</td>
            <td>{{ $customer->countOrder }}</td>

            @can('seeAdminSettings', \App\User::class)
                <td>{{ Helpers::amount($customer->total_price) }}</td>
            @endcan
        </tr>
    @endforeach
    </tbody>
</table>

<table width="100%" style="width:100%;margin-bottom:40px;" border="0">
    <thead>
    <tr>
        <th width="15%">@lang("customers.company_name")</th>
        <th width="25%">@lang("products.infos")</th>
        <th width="20%">@lang("products.nb_products")</th>

        @can('seeAdminSettings', \App\User::class)
            <th width="20%">@lang("products.price_ht")</th>
            <th width="20%">@lang("products.total_price_products")</th>
        @endcan
    </tr>
    </thead>

    <tbody>
    @foreach($customers as $customer)
        @foreach($customer->getTotalProducts() as $key => $item)
            <tr>
                <td>{{ (!empty($oldName) && $customer->company_name == $oldName) ? '' : $customer->company_name }}</td>
                <td>{{ $item['product']->showProductInfo(false) }}</td>
                <td>{{ $item['amount'] }}</td>

                @can('seeAdminSettings', \App\User::class)
                    <td>{{Helpers::amount( $item['product']->price_ht )}}</td>
                    <td>{{Helpers::amount( $item['total_price'] )}}</td>
                @endcan
            </tr>
            @php
                $oldName = $customer->company_name;
            @endphp
        @endforeach
    @endforeach
    </tbody>
</table>

<table width="100%" style="width:100%" border="0">
    <thead>
    <tr>
        <th width="30%">@lang("commun.name")</th>
        <th width="30%">@lang("customers.nb_orders")</th>

        @can('seeAdminSettings', \App\User::class)
            <th width="40%">@lang("customers.total_price_orders")</th>
        @endcan
    </tr>
    </thead>

    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->getLibelle() }}</td>
            <td>{{ $user->getCountOrders() }}</td>

            @can('seeAdminSettings', \App\User::class)
                <td>{{Helpers::amount($user->getTotalPriceOrders())}}</td>
            @endcan
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>