<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        body{
            font-size: 18px;
            color: grey;
        }
        table{
            width: 100%;
        }
        td{
            width: 100%;
        }
        .backgroundHeader{
            background-color: #FF8C21;
            height: 90px;
        }
        a{
            color:#FF8C21;
        }
    </style>

</head>
<body style="margin: 0px;padding: 0px">
<table>
    <tr>
        <td align="center" class="backgroundHeader">
            <a href="http://" style="font-size: 2em;text-decoration: none;color: lightslategrey">{{ config('app.name') }}</a>
        </td>
    </tr>
    <tr>
        <td  style="height: 40px"></td>
    </tr>
</table>
<table>
    <tr>
        <td style="width:10%"></td>
        <td style="width:80%">
            <table>
                <tr>
                    <td style="font-weight: bold;font-size: 1.2em">
                        Votre <span style="color:#FF8C21;">@lang('orders.order')</span> {{$order->code}} a été mise à jour !
                    </td>
                </tr>
                <tr>
                    <td  style="height: 10px"></td>
                </tr>
                <tr>
                    <td align="center">
                        <a href="{{route('member.orders.view', ['order' => $order->id ])}}"><button style="background-color: #FF8C21;color: white;padding: 10px 20px;border-radius: 5px;border: none;font-size: 0.9em">La voir maintenant</button></a>
                    </td>
                </tr>
                <tr>
                    <td  style="height: 30px"></td>
                </tr>
            </table>
        </td>
        <td style="width:10%"></td>
    </tr>
</table>
</body>