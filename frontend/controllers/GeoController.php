<?php
namespace frontend\controllers;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use GuzzleHttp\Client;
use yii\web\Response;
use frontend\models\Address as Address;

class GeoController extends Controller 
{
	public function actionIndex()
    {

    	$apikey = "e666f398-c983-4bde-8f14-e3fec900592a";
        $geocode = Yii::$app->request->get()['term'];
        $address = Address::find()->with('city')->where(['like', 'name', '%' . $geocode . '%', false])->asArray()->all();

        if (!empty($address)) {
            return json_encode($address, JSON_PRETTY_PRINT);
        }

        $geocode = str_replace(' ', '+', $geocode);
        
        $client = new Client();

        try {
            $request = new Request('GET', 'https://geocode-maps.yandex.ru/1.x/');

            $response = $client->send($request, [
	            'query' => [ 'apikey' => $apikey, 'geocode' => $geocode, 'format' => 'json']
	        ]);

            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException("Response error: " . $response->getReasonPhrase(), $request);
            }

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);

            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException("Invalid json format", $request);
            }

            if ($error = ArrayHelper::getValue($response_data, 'error.info')) {
                throw new BadResponseException("API error: " . $error, $request);
            }
            
            return json_encode($response_data, JSON_PRETTY_PRINT);

        } catch (RequestException $e) {
        	var_dump($e->getMessage());
            $result = true;
        }

        
    }
}