<?php

namespace App\Exports;

use App\Models\AmountProduct;
use App\Models\Customer;
use App\Models\Order;
use Helpers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class ExportStatsProductByCustomer implements FromView
{

    private $currentDay;
    private $dateEnd;

    public function __construct($currentDay, $dateEnd = null) {
        $this->currentDay = $currentDay;
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = Customer::exportProductByCustomerData($this->currentDay, $this->dateEnd);

        return view('admin.export.exportproductbycustomer', $data);
    }
}
