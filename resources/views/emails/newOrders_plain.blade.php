Nouvelles @lang('orders.orders') !

@foreach($orders as $order)
@lang('orders.order') {{ $order->code }} enregistrée de la part de {{ $order->name }} {{ $order->firstname }} !

La voir maintenant : {{route('admin.orders.view', ['order' => $order->id ])}}
@endforeach