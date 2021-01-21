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
        <th width="30">@lang("commun.name")</th>
        <th width="30">@lang("customers.nb_orders")</th>
        @can('seeAdminSettings', \App\User::class)
            <th width="40">@lang("customers.total_price_orders")</th>
        @endcan
    </tr>
    </thead>

    <tbody>
    @foreach($users as $user)
        <tr>
            <td>
                {{ data_get($user, 'user.libelle', '-') }}
            </td>
            <td>{{ $user->countOrder }}</td>
            @can('seeAdminSettings', \App\User::class)
                <td class='text-right'>{{Helpers::amount($user->total_price)}}</td>
            @endcan
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>