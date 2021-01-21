<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportAllStats;
use App\Exports\ExportStatsCustomer;
use App\Exports\ExportStatsOrder;
use App\Exports\ExportStatsProduct;
use App\Exports\ExportStatsProductByCustomer;
use App\Http\Controllers\Controller;
use App\Http\Requests\DateRequest;
use App\Models\AmountProduct;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class StatsController extends Controller
{

    /**
     * Show the stats page.
     *
     * @return \Illuminate\Http\Response
     */
    public function statsDay(DateRequest $request) {
        if($request->currentDay){
            $date = Carbon::createFromFormat('Y-m-d', $request->currentDay);
            $dateView = $request->currentDay;
        } else{
            $date = today();
            $dateView = $date->toDateString();
        }

    	// Get customers
        $queryCustomer = Customer::customerWithOrder();
    	$customersCurrentDay = $queryCustomer->Entered($date)->get();
    	
    	// Get users
    	$queryUser = User::userWithOrder();
    	$usersCurrentDay = $queryUser->Entered($date)->get();


    	// produits commandés par client
        $productsCurrentDay = Customer::getProductsByCustomer($date);

        // Ordered products
        $products = Product::getProductsWithOrder($date);

    	
        return view('admin.statsday', compact('customersCurrentDay', 'usersCurrentDay', 'productsCurrentDay', 'dateView', 'products', 'date'));
    }


    /**
     * @param DateRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statsRange(DateRequest $request){
        // If there is a start date
        $dateStart = null;
        $dateStartView = null;

        // If there is a end date
        $dateEnd = null;
        $dateEndView = null;
        if($request->date_range_start && $request->date_range_end){
            $dateStart = Carbon::createFromFormat('Y-m-d', $request->date_range_start)->startOfDay();
            $dateStartView = $request->date_range_start;

            $dateEnd = Carbon::createFromFormat('Y-m-d', $request->date_range_end)->endOfDay();
            $dateEndView = $request->date_range_end;
        } else{
            $dateStart = today()->subMonth()->startOfDay();
            $dateStartView = $dateStart->toDateString();

            $dateEnd = today()->endOfDay();
            $dateEndView = $dateEnd->toDateString();
        }


        // Get customers
        $queryCustomer = Customer::customerWithOrder();

        // Get users
        $queryUser = User::userWithOrder();

        // If dates entered
        if($dateStart != null && $dateEnd != null){
            $customersAllDay = $queryCustomer->Entered($dateStart, $dateEnd)->get();
            $usersAllDay = $queryUser->Entered($dateStart, $dateEnd)->get();

            // produits commandés par client
            $productsAllDay = Customer::getProductsByCustomer($dateStart, $dateEnd);

            // Ordered products
            $products = Product::getProductsWithOrder($dateStart, $dateEnd);

        } else{
            $customersAllDay = $queryCustomer->get();
            $usersAllDay = $queryUser->get();

            // produits commandés par client
            $productsAllDay = Customer::getProductsByCustomerWithoutDate();

            // Ordered products
            $products = Product::getProductsWithOrderWithoutDate();
        }


        return view('admin.statsrange', compact('customersAllDay', 'usersAllDay', 'productsAllDay', 'dateStartView', 'dateEndView', 'products', 'dateStart', 'dateEnd'));
    }


    /**
     * Export product data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportproductdata(Request $request){
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        $data = Product::exportProductData($request->currentDay, $request->dateEnd);

        $pdf = PDF::loadView('admin.export.exportproduct', $data);
        return $pdf->download('stats-commandes-par-produit-'.$now.'.pdf');
    }


    /**
     * Export product xls
     * @param Request $request
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportproductXLS(Request $request) {
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        return Excel::download(new ExportStatsProduct($request->currentDay, $request->dateEnd), 'stats-commandes-par-produit-'.$now.'.xlsx');
    }


    /**
     * Export customer data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportcustomerdata(Request $request){
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        $data = Customer::exportCustomerData($request->currentDay, $request->dateEnd);

        $pdf = PDF::loadView('admin.export.exportcustomer', $data);
        return $pdf->download('stats-commandes-par-client-'.$now.'.pdf');
    }


    /**
     * Export customer xls
     * @param Request $request
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportcustomerXLS(Request $request) {
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        return Excel::download(new ExportStatsCustomer($request->currentDay, $request->dateEnd), 'stats-commandes-par-client-'.$now.'.xlsx');
    }


    /**
     * Export product by customer data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportproductbycustomerdata(Request $request){
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        $data = Customer::exportProductByCustomerData($request->currentDay, $request->dateEnd);

        $pdf = PDF::loadView('admin.export.exportproductbycustomer', $data);
        return $pdf->download('stats-commandes-produit-par-client-'.$now.'.pdf');
    }


    /**
     * Export product by customer xls
     * @param Request $request
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportproductbycustomerXLS(Request $request) {
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        return Excel::download(new ExportStatsProductByCustomer($request->currentDay, $request->dateEnd), 'stats-commandes-produit-par-client-'.$now.'.xlsx');
    }


    /**
     * Export order data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportorderdata(Request $request){
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        $data = User::exportUserData($request->currentDay, $request->dateEnd);

        $pdf = PDF::loadView('admin.export.exportorder', $data);
        return $pdf->download('stats-commandes-par-user-'.$now.'.pdf');
    }


    /**
     * Export order xls
     * @param Request $request
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportorderXLS(Request $request) {
        $now = Carbon::now()->format('Y-m-d--H-i-s');

        return Excel::download(new ExportStatsOrder($request->currentDay, $request->dateEnd), 'stats-commandes-par-user-'.$now.'.xlsx');
    }


//    /**
//     * Export all data
//     * @param Request $request
//     * @return \Illuminate\Http\Response
//     */
//    public function exportalldata(Request $request){
//        $now = Carbon::now()->format('Y-m-d--H-i-s');
//
//        $customers = Customer::getCustomerWithOrders();
//        $users = User::notAdmin()->get();
//
//        $data = [
//            'title' => __('commun.all_stats'),
//            'heading' => __('commun.all_stats'),
//            'customers' => $customers,
//            'users' => $users,
//        ];
//
//        $pdf = PDF::loadView('admin.export.exportalldata', $data);
//        return $pdf->download('statistiques-'.$now.'.pdf');
//    }
//
//
//    /**
//     * Export all stats xls
//     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
//     * @throws \PhpOffice\PhpSpreadsheet\Exception
//     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
//     */
//    public function exportallXLS() {
//        $now = Carbon::now()->format('Y-m-d--H-i-s');
//
//        return Excel::download(new ExportAllStats, 'statistiques-'.$now.'.xlsx');
//    }
}
