<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $title }}</title>
</head>
<body>
<h1>{{ $heading.'-'.$dateTitle }}</h1>

<table width="100%" style="width:100%" border="0">
    <thead>
        <tr>
            <th width="20">@lang("customers.company_name")</th>
            <th width="40">@lang("customers.nb_orders")</th>

            @can('seeAdminSettings', \App\User::class)
                <th width="40">@lang("customers.total_price_orders")</th>
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
</body>
</html>