<?php
    header("Content-Type: application/json");
    
    require_once "../GetFunctions.php";
    require_once "../secure/api.php";
    require_once "../MatchTypes.php";
    
    $summonerName = $_GET["summonerName"];
    $summonerRegion = $_GET["region"];
    
    $summonerName = preg_replace("/\s*/", "", $summonerName);
    $summonerName = strtolower($summonerName);
    
    $nameURL = "https://".$summonerRegion.".api.riotgames.com/lol/summoner/v3/summoners/by-name/".$summonerName."?api_key=".$api_key;
    
    $dataDragonVersionURL = "https://ddragon.leagueoflegends.com/api/versions.json";
    $dataDragonVersion = GetDataDragonVersion($dataDragonVersionURL);
    $dataDragonIconURL = "http://ddragon.leagueoflegends.com/cdn/".$dataDragonVersion."/img/profileicon/";
    
    function BuildChampionArray($dDragonVersion)
    {
        $championURL = "https://ddragon.leagueoflegends.com/cdn/".$dDragonVersion."/data/en_US/champion.json";
        $championData = GetChampionInformation($championURL);
        $championJSON = json_decode($championData);
        $aChampionArray = [];

        foreach ($championJSON->data as $champion)
        {
            $aChampionArray[$champion->key] = $champion->id;
        }

        return $aChampionArray;
    }
    
    $summonerData = GetSummonerInformation($nameURL);
    $summonerJSON = json_decode($summonerData);
    $summonerId = $summonerJSON->id;
    
    $leagueURL = "https://".$summonerRegion.".api.riotgames.com/lol/league/v3/positions/by-summoner/".$summonerId."?api_key=".$api_key;
    $leagueData = GetLeagueInformation($leagueURL);
    $leagueJSON = json_decode($leagueData);
    
    $liveGameURL = "https://".$summonerRegion.".api.riotgames.com/lol/spectator/v3/active-games/by-summoner/".$summonerId."?api_key=".$api_key;
    $liveGameData = GetLiveGameInformation($liveGameURL);

    $championArray = BuildChampionArray($dataDragonVersion);
    
    if ($liveGameData != NULL)
    {
        $liveGameJSON = json_decode($liveGameData);
        
        $returnArray = array();
        
        $returnArray["BannedChampions"] = array();
        $returnArray["BlueSide"] = array();
        $returnArray["RedSide"] = array();
        $x = 0;       
        $duplicateBan = false;
        
        /*foreach ($liveGameJSON->bannedChampions as $banned)
        {
            foreach($returnArray["BannedChampions"] as $checkBan)
            {
                if ($banned->championId == $checkBan)
                {
                    $duplicateBan = true;
                }
            }
            if ($duplicateBan == false)
            {
                $returnArray["BannedChampions"][$x] = $championArray[$banned->championId];
                $x++;
            }
            else if ($duplicateBan == true)
            {
                $duplicateBan = false;
            }
        }*/
        
        $matchDetails = GetMapAndMode($liveGameJSON, $matchTypes, $mapTypes);
        $returnArray["mode"] = $matchDetails["modeName"];
        $returnArray["map"] = $matchDetails["mapName"];
        
        $x = 0;
        $y = 0;
        
        foreach ($liveGameJSON->participants as $summoner)
        {
            switch ($summoner->teamId)
            {
                case 100:
                    $returnArray["BlueSide"][$x] = $summoner;
                    $returnArray["BlueSide"][$x]->championName = $championArray[$summoner->championId];
                    $x++;
                    break;
                case 200:
                    $returnArray["RedSide"][$y] = $summoner;
                    $returnArray["RedSide"][$y]->championName = $championArray[$summoner->championId];
                    $y++;
                    break;
            }
        }
    }
    
    unset($api_key);
    
    echo json_encode($returnArray);
?>