<?php

namespace App\Jobs;

use App\Productcron;
use App\Products;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;



class ProcessProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Set UP Client & cURL request
        $headers=['authkey'=>env("API_SECRET")];
        $client = new Client();
        $response = $client->request('GET','http://www.poolsupplyworld.com/api.cfm',['headers'=>$headers]);
        $body = $response->getBody()->getContents();
        $body  = json_decode($body);

        //Check Products if they exist and Insert if new.
        if(is_array($body))
        {
            //Get list of current products
            $currentProducts = DB::table('productcron')->pluck('id')->toArray();

            //Loop through CURL Array
            foreach($body as $product_id)
            {
                //Check if Id exsists and remove from current Products
                $key =  array_search($product_id,$currentProducts);
                if($key!==null)
                {
                    unset($currentProducts[$key]);
                }
                //Add update Product ID
                Productcron::firstOrCreate(['id'=>$product_id]);
            }
            //Deactivate any remaining Prodcust from Current listed
            foreach($currentProducts as $product_id)
            {
                DB::table('products')
                    ->where('id', $product_id)
                    ->update(['active' => 0]);

            }

        }
    }
}
