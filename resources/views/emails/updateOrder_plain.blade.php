Votre @lang('orders.order') {{$order->code}} a été mise à jour !

La voir maintenant : {{route('member.orders.view', ['order' => $order->id ])}}