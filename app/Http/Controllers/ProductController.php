<?php

namespace App\Http\Controllers;


use App\Products;
use App\Productcron;
use App\Tracklog;
use DB;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Jenssegers\Agent\Agent;



class ProductController extends Controller
{

    public function index(Request $request)
    {
        $this->tracker($request,'Product','Index');
        return view('main');
    }

    /**
    *Search
    */
    public function search(Request $request)
    {

        if($request->input('_token') != session('_token'))
        {
            $products = [];
        }
        else {

            $this->tracker($request,'Product','Search',$request->input('search'));

            //A better search engine can be created here
            try{
                $search = trim($request->input('search'));
                $products = DB::table('products')
                    ->where('brand', 'like', '%'.$search.'%')
                    ->orWhere('type', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orderBy('brand','ASC')
                    ->orderBy('type','ASC')
                    ->orderBy('track_click','DESC')
                    ->get();
            }catch(Exception $e)
            {
                report($e->message());
                return;
            }
            if($products->count())
            {
                foreach($products as $k=>$product)
                {
                    $products[$k]->images = unserialize($product->images);
                    $products[$k]->data = null;
                    $products[$k]->seo_name = $this->seoname($product->name);
                }
            }
        }

        return view('elements.products',['products'=>$products]);
    }
    //AJAX Get initial Content
    public function initialload(Request $request)
    {
        //check CSRF Token return empty set if no token

        if($request->query('csrf') != session('_token'))
        {
            return redirect('/');
        }
        else {


            $products = DB::table('products')->where('active',1)
                ->orderBy('track_click','DESC')
                ->get();
            if($products->count())
            {
                foreach($products as $k=>$product)
                {
                    $products[$k]->images = unserialize($product->images);
                    $products[$k]->data = null;
                    $products[$k]->seo_name = $this->seoname($product->name);
                }
            }
        }

        return view('elements.products',['products'=>$products]);


    }
    public function product(Request $request,$id)
    {

        $id = explode('-',$id);
        $product = DB::table('products')->whereId($id[0])->get();
        if($product->count()==0)
        {
            return redirect('/');
        }
        $product[0]->images = unserialize($product[0]->images);
        $product[0]->data = null;
        //Pull Similar Products
        $similar_products = DB::table('products')->select('id','name','images')->where('type',$product[0]->type)->get();

        if($similar_products->count())
        {
            //Clean List up.
            foreach($similar_products as $k=>$sm_product)
            {

                $similar_products[$k]->images = unserialize($sm_product->images);
                $similar_products[$k]->seo_name = $this->seoname($sm_product->name);
            }
        }
        //Track Type of Item Traffic
        if(strpos($request->headers->get('referer'),$request->getHttpHost()))
        {
            $this->trackClick($id[0]);
        }
        else {
            $this->trackOrganic($id[0]);
        }
        $this->tracker($request,'Product','Product Click',$product[0]->name);
        return view('product',['product'=>$product[0],'similar_products'=>$similar_products]);


    }

    public function admin(Request $request)
    {
        if($request->query('csrf') != session('_token'))
        {
            return redirect('/');
        }
        else {
            $results = DB::table('tracklog')->orderBy('session_id','ASC')->orderBy('created_at','ASC')->get();

            if($results->count())
            {
                $tracked = [];
                foreach($results as $res)
                {
                    $tracked[$res->session_id]['session_id'] = $res->session_id;
                    $tracked[$res->session_id]['client'] = $res->client;
                    $tracked[$res->session_id]['mobile'] = $res->mobile;
                    //$res->created_at = date('M j, Y G:i A',strtotime($res->created_at));
                    $tracked[$res->session_id]['data'][] = $res;

                }
                //free memory
                $results=null;
            }

            return view('admin',['tracked'=>$tracked]);


        }
    }


/*
*PRIVATE FUNCTIONS
*/

    //Tracks Basic Traffic
    private function tracker($request, $controller,$method,$note="")
    {

        //Next TIme split table on into session info, next into dynamic data.
        $agent = new Agent();
        $tracker = new Tracklog;
        $tracker->session_id = session()->getID();
        $tracker->controller =$controller;
        $tracker->method = $method;
        $tracker->url = $request->url();
        $tracker->from_url = $request->headers->get('referer');
        $tracker->client = $request->userAgent();
        $tracker->mobile = $agent->isMobile();
        $tracker->notes = $note;

        $tracker->save();


    }
    //Increment Organic Traffic
    private function trackOrganic($product_id)
    {
        DB::table('products')->whereId($product_id)->increment('track_organic');
    }
    //Track Clicks;
    private function trackClick($product_id)
    {
        DB::table('products')->whereId($product_id)->increment('track_click');
    }
    //Return SEO Friendly name
    private function seoname($name)
    {
        $name = trim(strtolower(preg_replace('/[^A-z0-9 ]/','',$name)));
        $name = preg_replace('/\s{2,}/',' ',$name);
        return str_replace(' ','-',$name);

    }

}
