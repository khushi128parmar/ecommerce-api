<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        // TOTAL SALES
        $totalSales = Order::where(
            'payment_status',
            'paid'
        )
            ->sum('total');

        // TOTAL ORDERS
        $totalOrders = Order::count();

        // TOTAL USERS
        $totalUsers = User::count();

        // TOTAL PRODUCTS
        $totalProducts = Product::count();

        // LATEST ORDERS
        $latestOrders = Order::latest()
            ->take(5)
            ->get();

        // MONTHLY SALES
        $monthlySales = Order::select(

            DB::raw('MONTH(created_at) as month'),

            DB::raw('SUM(total) as total')
        )
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->get();

        $topProducts = DB::table('order_items')

            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_sales')
            )

            ->groupBy('product_id')

            ->orderByDesc('total_sales')

            ->take(5)

            ->get();

        return $this->successResponse(
            'Dashboard data fetched successfully',
            [

                'total_sales' => $totalSales,

                'total_orders' => $totalOrders,

                'total_users' => $totalUsers,

                'total_products' => $totalProducts,

                'latest_orders' => $latestOrders,

                'monthly_sales' => $monthlySales,

                'top_products' => $topProducts,
            ]
        );
    }
}
