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
        <th width="20">@lang("products.code")</th>
        <th width="20">@lang("products.name")</th>
        <th width="20">@lang("products.nb_products")</th>
        @can('seeAdminSettings', \App\User::class)
            <th width="20">@lang("products.price_ht")</th>
            <th width="20">@lang("products.total_price_products")</th>
        @endcan
    </tr>
    </thead>
    <tbody>
    @foreach($productWithOrder as $products)
        @foreach($products as $product)
            <tr>
                <td>{{ $product->product->code }}</td>
                <td>
                   {{ $product->product->name }}
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
</body>
</html>