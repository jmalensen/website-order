Nouvelle @lang('orders.order') !

Une nouvelle @lang('orders.order') a été enregistrée de la part de {{ $order->name }} {{ $order->firstname }} !

La voir maintenant : {{route('admin.orders.view', ['order' => $order->id ])}}