<?php

namespace App\Exports;

use App\Models\AmountProduct;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Helpers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class ExportStatsProduct implements FromView
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
        $data = Product::exportProductData($this->currentDay, $this->dateEnd);

        return view('admin.export.exportproduct', $data);
    }
}
