<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Auth;
use Hash;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Intervention\Image\Laravel\Facades\Image;
use ProtoneMedia\Splade\Facades\Toast;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::user()->getRoleNames()[0] == "user"){
            return Redirect::to('dashboard/partner_registration');
        }

        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin')){

            $users = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('created_at', '>', Carbon::now()->subDays(6))->groupBy('date')->orderBy('date', 'ASC')->get()->toArray();
            $traffic = Visitor::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('created_at', '>', Carbon::now()->subDays(6))->groupBy('date')->orderBy('date', 'ASC')->get()->toArray();
            $traffic_platform = Visitor::select('platform', DB::raw('count(*) as total'))->where('platform', '!=', "")->where('created_at', '>', Carbon::now()->subDays(6))->groupBy('platform')->get()->toArray();
    
            $arr_users = [];
            $arr_traffics = [];
            $period = CarbonPeriod::create(Carbon::now()->subDays(6), Carbon::now());
            $total_users_chart = 0;
            $total_traffics_chart = 0;
            $per_users_chart = 0;
            $per_traffics_chart = 0;
            foreach ($period as $idx => $date) {
                $arr_user = [];
                $arr_traffic = [];
                foreach ($traffic as $value) {
                    if ($date->format('Y-m-d') == $value['date']) {
                        $total_traffics_chart = $total_traffics_chart + $value['total'];
                        array_push($arr_traffic, $value);
                        array_push($arr_traffics, $value);
                    }
                }
                if ($arr_traffic == []) {
                    array_push($arr_traffics, array(
                        "date" => $date->format('Y-m-d'),
                        "total" => 0,
                    )
                    );
                }
                foreach ($users as $value) {
                    if ($date->format('Y-m-d') == $value['date']) {
                        $total_users_chart = $total_users_chart + $value['total'];
                        array_push($arr_user, $value);
                        array_push($arr_users, $value);
                    }
                }
                if ($arr_user == []) {
                    array_push($arr_users, array(
                        "date" => $date->format('Y-m-d'),
                        "total" => 0,
                    )
                    );
                }
            }
    
            // $chart = (new LarapexChart)
            //     ->lineChart()
            //     ->addLine('Users', (array) array_values((array) $users))
            //     ->setXAxis(["26-07", "27-07"])
            //     ->setTitle('Chart Visitors')
            //     ->toVue();
    
            // $donut_chart = (new LarapexChart)->donutChart()
            //     ->setTitle('Top 3 scorers of the team.')
            //     ->setSubtitle('Season 2021.')
            //     ->addData([20, 24, 30])
            //     ->setLabels(['Player 7', 'Player 10', 'Player 9'])
            //     ->toVue();
    
            // $radial_chart = (new LarapexChart)->radialChart()
            //     ->setTitle('Passing effectiveness.')
            //     ->setSubtitle('Barcelona city vs Madrid sports.')
            //     ->addData([75, 60])
            //     ->setLabels(['Barcelona city', 'Madrid sports'])
            //     ->setColors(['#D32F2F', '#03A9F4'])
            //     ->toVue();
    
            // $polar_area_chart = (new LarapexChart)->polarAreaChart()
            //     ->setTitle('Top 3 scorers of the team.')
            //     ->setSubtitle('Season 2021.')
            //     ->addData([20, 24, 30])
            //     ->setLabels(['Player 7', 'Player 10', 'Player 9'])
            //     ->toVue();
    
            $pie_chart = (new LarapexChart)->pieChart()
                ->addData(array_column($traffic_platform, 'total'))
                ->setLabels(array_column($traffic_platform, 'platform'))
                ->setSparkline()
                ->setHeight(250)
                ->toVue();
    
            $area_chart = (new LarapexChart)->areaChart()
                ->addData('New Users', array_column($arr_users, 'total'))
                ->setXAxis(array_column($arr_users, 'date'))
                ->setHeight(230)
                ->setSparkline()
                ->setColors(['#ffc63b'])
                ->toVue();
    
            $area_chart_traffic = (new LarapexChart)->areaChart()
                ->addData('Total Traffic', array_column($arr_traffics, 'total'))
                ->setXAxis(array_column($arr_traffics, 'date'))
                ->setHeight(230)
                ->setSparkline()
                ->setColors(['#ff6384'])
                ->toVue();
    
            // $bar_chart = (new LarapexChart)->barChart()
            //     ->setTitle('San Francisco vs Boston.')
            //     ->setSubtitle('Wins during season 2021.')
            //     ->addData('San Francisco', [6, 9, 3, 4, 10, 8])
            //     ->addData('Boston', [7, 3, 8, 2, 6, 4])
            //     ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June'])
            //     ->toVue();
    
            // $horizontal_bar_chart = (new LarapexChart)->horizontalBarChart()
            //     ->setTitle('Los Angeles vs Miami.')
            //     ->setSubtitle('Wins during season 2021.')
            //     ->setColors(['#FFC107', '#D32F2F'])
            //     ->addData('San Francisco', [6, 9, 3, 4, 10, 8])
            //     ->addData('Boston', [7, 3, 8, 2, 6, 4])
            //     ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June'])
            //     ->toVue();
    
            // $hear_map_chart = (new LarapexChart)->heatMapChart()
            //     ->setTitle('Basic radar chart')
            //     ->addData('Sales', [80, 50, 30, 40, 100, 20])
            //     ->addHeat('Income', [70, 10, 80, 20, 60, 40])
            //     ->setMarkers(['#FFA41B', '#4F46E5'], 7, 10)
            //     ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June'])
            //     ->toVue();
    
            // $radar_chart = (new LarapexChart)->radarChart()
            //     ->setTitle('Individual Player Stats.')
            //     ->setSubtitle('Season 2021.')
            //     ->addData('Stats', [70, 93, 78, 97, 50, 90])
            //     ->setXAxis(['Pass', 'Dribble', 'Shot', 'Stamina', 'Long shots', 'Tactical'])
            //     ->setMarkers(['#303F9F'], 7, 10)
            //     ->toVue();
    
            return view('dashboard', [
                'total_traffics_chart' => $total_traffics_chart,
                'total_users_chart' => $total_users_chart,
                'count_visitors_today' => Visitor::whereDate('created_at', Carbon::today())->count(),
                // 'chart' => $chart,
                'pie_chart' => $pie_chart,
                // 'bar_chart' => $bar_chart,
                // 'donut_chart' => $donut_chart,
                // 'radial_chart' => $radial_chart,
                // 'polar_area_chart' => $polar_area_chart,
                'area_chart' => $area_chart,
                'area_chart_traffic' => $area_chart_traffic,
                // 'horizontal_bar_chart' => $horizontal_bar_chart,
                // 'hear_map_chart' => $hear_map_chart,
                // 'radar_chart' => $radar_chart,
            ]);
        } else {
            $products = Product::select('name', 'total_comments', 'views_counter', 'ratings')->where('user_id', Auth::user()->id)->orderBy('views_counter', 'DESC')->get()->toArray();
            $services = Service::select('name', 'total_comments', 'views_counter', 'ratings')->where('user_id', Auth::user()->id)->orderBy('views_counter', 'DESC')->get()->toArray();
            $popular_products = array_slice($products, 0, 5);
            $popular_services = array_slice($services, 0, 5);
            return view('dashboard', [
                'total_product' => count($products),
                'total_service' => count($services),
                'popular_products' => $popular_products,
                'popular_services' => $popular_services,
            ]);
        }
        
    }
}
