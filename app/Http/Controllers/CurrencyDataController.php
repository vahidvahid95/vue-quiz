<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpClient\Exception\ClientException;


class CurrencyDataController extends Controller
{
    public function getRates(){

        $client = new Client();
        $currentCurrency = null;
        $out = [];
        try{
            $crawler = $client->request('GET', 'https://arzi24.com/%d9%82%db%8c%d9%85%d8%aa-%d8%a7%d8%b1%d8%b2/');
            $rows = $crawler->filter('.arz_page_tbl>table>tbody>tr>td')->each(function ($node, $i) use (&$out) {
                if($i % 3 === 0){
                    $out[($i/3)+$i%3][0] = $node->text();
                    // currency title
                }
                elseif ($i % 3 === 1){
                    $out[(($i/3)+$i%3)-1][1] = intval($node->text())*10;
                    // *10: Toman to Rial
                    //currency sell
                }
                else{
                    $out[(($i/3)+$i%3)-2][2] = intval($node->text())*10;
                    // *10: Toman to Rial
                    //currency buy
                }
            });
            return response(json_encode($out),200);
        }catch (ClientException $e){
            return response()->json('Something Wrong Happened', $e->getCode());
        }
    }
    public function validateImage(Request $request){
        $rules = array(
            'file' => 'mimes:jpeg,jpg,png,gif|required|max:10000|dimensions:width=512,height=512'
        );
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        else{
            return response()->json(['message' => 'validated'], 200);
        }

    }
}
