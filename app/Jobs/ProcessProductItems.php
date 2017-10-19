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

class ProcessProductItems implements ShouldQueue
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
        //
        $productcron_results = Productcron::all();

        if($productcron_results->count())
        {
            foreach($productcron_results as $product_id)
            {

                $prod_result = Products::where(['id'=>$product_id->id])->first();

                //Check if Prod is null or scheduled is past due date;
                if(!$prod_result || strtotime($prod_result->scheduled) < time())
                {
                        //create or Update then break;
                        $headers=['authkey'=>env("API_SECRET")];
                        $client = new Client();
                        $response = $client->request('GET','http://www.poolsupplyworld.com/api.cfm',
                            ['headers'=>$headers,'query'=>['productid'=>$product_id->id]]);
                        $body = $response->getBody()->getContents();
                        $body  = json_decode($body);
                        $id = $body->id;
                        $dataArr = [
                            'name'=>$body->name,
                            'brand'=>$body->brand,
                            'type'=>$body->type,
                            'aboveground'=>(isset($body->aboveground)?$body->aboveground:NULL),
                            'description'=>$body->description,
                            'images'=>serialize($body->images),
                            'data'=>serialize($body),
                            'scheduled'=>date('Y-m-d H:i:s',time()+(3600*4))

                        ];
                        Products::updateOrCreate(['id'=>$id],$dataArr);

                    break;
                }
            }
        }
    }
}
