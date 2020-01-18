<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class weatherController extends Controller
{
    public function index(){

        $forecast = $this->getAPI();
        $arrayWithNightInfo = $this->informationAtNightHours($forecast);
        $clear = $this->isClear($arrayWithNightInfo);
        $moonPhase = $this->moonPhases($forecast);
        
        return view("welcome", ['nightHoursInformations' => $arrayWithNightInfo, 'clear' => $clear, 'moonPhase' => $moonPhase]);
        //$arrayWithNightInfo = array_splice($arrayWithNightInfo,0,3);
    }

    /** Checken of het helder wordt */
    public function isClear($nightHoursJSON) {
        foreach($nightHoursJSON as $hour){
            if ($hour->cloudCover < .1) {
               return true;
            }
        }
        return false;
    }

    /* Maanfase */
    public function moonPhases($forecast) {
        $newMoonPhase = $forecast->daily->data[0]->moonPhase;
        if ($newMoonPhase >= 0.95) {
            $newMoonPhase = 0;
        } 
        //$newMoonPhase = 0.5;
        $moonPhase = round($newMoonPhase * 10);
        return $moonPhase;
    }

    /* Nachturen berekenen */
    public function informationAtNightHours($forecast) {
        $lastSunHourToday = date('H', $forecast->daily->data[0]->sunsetTime);
        $firstNightHour = ($lastSunHourToday + 1);
        $lastNightHourTomorrow = date('H', $forecast->daily->data[1]->sunriseTime);
        $lastNightHour = ($lastNightHourTomorrow - 1);
        
        $endHour24 = ($lastNightHour + 24);

        $firstHourInJSON = date('H',$forecast->hourly->data[0]->time);
        $nightHoursWithInformationArray = [];
        while($firstNightHour <= $endHour24) {

            $nightHours[] = $firstNightHour % 24;
            $firstNightHour += 1;
        
            $nightlyHours = $firstNightHour - $firstHourInJSON;
            if ($nightlyHours > 0) {
                $jsonForHour =  $forecast->hourly->data[$firstNightHour - $firstHourInJSON];
                $nightHoursWithInformationArray [] =  $jsonForHour;
            }
        }
        return $nightHoursWithInformationArray;
    }

    /* Data ophalen */
    public function getAPI() {
        $api_url = 'https://api.darksky.net/forecast/d9886212e11a316fd79e9a08815df546/53.143040,5.849060/?lang=nl&units=ca';
        $forecast = json_decode(file_get_contents($api_url));
        return $forecast;
    }

    public function sendAPIData(){
        $forecast = $this->getAPI();
        $arrayWithNightInfo = $this->informationAtNightHours($forecast);
        $clear = $this->isClear($arrayWithNightInfo);
        $moonPhase = $this->moonPhases($forecast);

        $data = new \stdClass();
        $data->forecast = $forecast;
        $data->nightHours = $arrayWithNightInfo;
        $data->isClear = $clear;
        $data->moonPhase = $moonPhase;
        
        return response()->json($data);
    }
}