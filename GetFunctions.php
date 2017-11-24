<?php

//Data Dragon API call to check Data Dragon version
    function GetDataDragonVersion($ddVersionURL)
    {
        $ch = curl_init($ddVersionURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $version = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($version);
        return $json[0];
    }

    //API call to get summoner profile information
    function GetSummonerInformation($sUserURL)
    {
        $ch = curl_init($sUserURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    //API call to get summoner league information
    function GetLeagueInformation($sLeagueURL)
    {
        $ch = curl_init($sLeagueURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $leagueInfo = curl_exec($ch);
        curl_close($ch);
        return $leagueInfo;
    }
            
    //API call to get live game information
    function GetLiveGameInformation($sLiveURL)
    {
        $ch = curl_init($sLiveURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $liveGameData = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpcode != 404)
        {
            return $liveGameData;
        }
        else
        {
            return NULL;
        }
    }
            
    function GetChampionInformation($sChampionURL)
    {
        $ch = curl_init($sChampionURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $championInfo = curl_exec($ch);
        curl_close($ch);
        return $championInfo;
    }

?>