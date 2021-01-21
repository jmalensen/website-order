<?php

namespace App\Exports;

use App\Models\Customer;
use App\User;
use Helpers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportAllStats implements FromView
{

    /**
     * @return View
     */
    public function view(): View {
    	$customers = Order::Stats()
			->groupBy('customer_id')
			->select('customer_id', DB::raw('count(*) as countOrder'), DB::raw('sum(total_price) as total_price'))
			->with(['customer'])
			->get();

        $users = User::notAdmin()->get();

        $data = [
            'title' => __('commun.all_stats'),
            'heading' => __('commun.all_stats'),
            'customers' => $customers,
            'users' => $users,
        ];

        return view('admin.export.exportalldata', $data);
    }
}
