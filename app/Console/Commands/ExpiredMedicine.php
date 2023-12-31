<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
class ExpiredMedicine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expired-medicine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {

                $medicines = $warehouse->medicines;
                $weekAgo = Carbon::now()->subWeek();

                $records = $medicines->wherePivot('final_date', '>', $weekAgo)->get();

                foreach ($records as $record) {
                    // اشعار
                }

                // بعات لكل الادمن تبع هالمستودع
                $admins=User::where('warehouse_id',$warehouse->id)->get();
            }
        }




}
