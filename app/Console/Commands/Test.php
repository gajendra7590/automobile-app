<?php

namespace App\Console\Commands;

use App\Models\RtoRegistration;
use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->updateDuesOrPaidBal();
        // $this->updateChasisNumberInRtoRegistration();
        $this->updateCustomerBalance();
    }

    /**
     * Update customer balance
     */
    public function updateCustomerBalance() {
        $sales = Sale::select('*')
        // ->where('id','2')
        ->chunk(100,function($sales){
            foreach($sales as $k => $sale) {
                $saleData = $sale->toArray();
                $full_name = strtoupper(custPrefix($saleData['customer_gender']) .' '.$saleData['customer_name'].' '.custRel($saleData['customer_relationship']).' '.$saleData['customer_guardian_name']);
                $sale->customer_full_name = $full_name;
                $sale->save();
            }
        });

    }

    public function updateChasisNumberInRtoRegistration()
    {
        RtoRegistration::with([
            'sale' => function ($sale) {
                $sale->with([
                    'purchase' => function ($purchase) {
                        $purchase->with([
                            'purchase_transfer_latest' => function ($purchase_tf_latest) {
                                $purchase_tf_latest->select('id', 'purchase_id', 'broker_id', 'status')
                                    ->with([
                                        'brokr:id,name,email'
                                    ]);
                            }
                        ]);
                    }
                ]);
            }
        ])->orderBy('id', 'DESC')->chunk(100, function ($rtoData) {
            foreach ($rtoData as $rtoOne) {
                $rtoOne = $rtoOne->toArray();
                echo "ID - " . $rtoOne['id'] . "\n";
                RtoRegistration::where('id', $rtoOne['id'])->update([
                    'chasis_number' => isset($rtoOne['sale']['purchase']['vin_number']) ? $rtoOne['sale']['purchase']['vin_number'] : null,
                    'engine_number' => isset($rtoOne['sale']['purchase']['engine_number']) ? $rtoOne['sale']['purchase']['engine_number'] : null,
                    'broker_name'   => (isset($rtoOne['sale']['purchase']['purchase_transfer_latest']['brokr']['name']) && ($rtoOne['sale']['purchase']['purchase_transfer_latest']['status'] == '0')) ? $rtoOne['sale']['purchase']['purchase_transfer_latest']['brokr']['name'] : null,
                    'broker_id'     => (isset($rtoOne['sale']['purchase']['purchase_transfer_latest']['brokr']['id']) && ($rtoOne['sale']['purchase']['purchase_transfer_latest']['status'] == '0')) ? $rtoOne['sale']['purchase']['purchase_transfer_latest']['brokr']['id'] : null,
                ]);
            }
        });
        return true;
    }

    public function updateDuesOrPaidBal()
    {
        $salesAccounts = SalePaymentAccounts::select('id', 'status')->get();
        foreach ($salesAccounts as $salesAccount) {
            echo "ID - " . $salesAccount->id . "\n";
            updateDuesOrPaidBalance($salesAccount->id);
        }
        return 0;
    }
}
