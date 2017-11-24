<!DOCTYPE html>

<!-- ADD CHECKING FOR MULTIPLE DUPLICATE BANS  -->
<html>
    <head>
        <?php
            require_once '/secure/api.php';
            require_once 'GetFunctions.php';
            require_once '/secure/db.php';
            include_once 'MatchTypes.php';
            $summonerName = $_GET["summonerName"];
            $summonerRegion = $_GET["region"];
            
            $db = new mysqli($location, $username, $access, $dbname);
            
            if ($db->connect_error)
            {
                echo "Connection failed: ".$db->connect_error;
            }
            
            //clean summoner name before use
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
            
            //Get Summoner Profile Data
            $summonerData = GetSummonerInformation($nameURL);
            $summonerJSON = json_decode($summonerData);
            
            //Get Summoner ID for further requests
            $summonerId = $summonerJSON->id;
            
            //Get League Data for summoner
            $leagueURL = "https://".$summonerRegion.".api.riotgames.com/lol/league/v3/positions/by-summoner/".$summonerId."?api_key=".$api_key;
            $leagueData = GetLeagueInformation($leagueURL);
            $leagueJSON = json_decode($leagueData);
            
            //Get Spectator Information
            $liveGameURL = "https://".$summonerRegion.".api.riotgames.com/lol/spectator/v3/active-games/by-summoner/".$summonerId."?api_key=".$api_key;
            $liveGameData = GetLiveGameInformation($liveGameURL);
            
            //Build Champion Array. Keys are Champion IDs, Values are Champion names
            $championArray = BuildChampionArray($dataDragonVersion);
            
            //check if summoner is in a live game
            if ($liveGameData != NULL)
            {
                $liveGameJSON = json_decode($liveGameData);
            }
            
            $db->close();
            
            #unset API KEY HERE
            unset($api_key);
        ?>
        <title>Summoner Information for <?php echo $summonerName ?></title>
    </head>
    <body>
        <p>
            <?php
                echo "<p>Summoner ID:".$summonerId."</p>";
                echo "<p>Account ID:".$summonerJSON->accountId."</p>";
                echo "<p>Summoner Name:".$summonerJSON->name."</p>";
                echo "<p>Summoner Icon ID:".$summonerJSON->profileIconId."<br /><img src='".$dataDragonIconURL.$summonerJSON->profileIconId.".png' width='150px' height='150px'/></p>";
                foreach ($leagueJSON as $league)
                {
                    echo "<p>";
                    if ($league->queueType == "RANKED_FLEX_SR")
                    {
                        echo "FLEX 5v5:<br />".$league->leagueName." - ".$league->tier." ".$league->rank;
                    }
                    else if ($league->queueType == "RANKED_SOLO_5x5")
                    {
                        echo "SOLO/DUO:<br />".$league->leagueName." - ".$league->tier." ".$league->rank;
                    }
                    else if ($league->queueType == "RANKED_FLEX_TT")
                    {
                        echo "FLEX 3v3:<br />".$league->leagueName." - ".$league->tier." ".$league->rank;
                    }
                    echo "</p>";
                }
                if ($liveGameData != NULL)
                {
                    $matchDetails = GetMapAndMode($liveGameJSON, $matchTypes, $mapTypes);
                    echo "<p><h3>Live Game Information:</h3></p>";
                    echo "<p><h5>".$matchDetails["modeName"]."<br />".$matchDetails["mapName"]."</h5></p>";
                    echo "<table><tr><th>Blue Team</th></tr>";
                    echo "<tr><td><h6>Summoner Name</h6></td><td><h6>Champion</h6></td></tr>";
                    foreach ($liveGameJSON->participants as $blueSummoner)
                    {
                        if ($blueSummoner->teamId == 100)
                        {
                            echo "<tr><td>".$blueSummoner->summonerName."</td><td>".$championArray[$blueSummoner->championId]."</td></tr>";
                        }
                    }
                    $bannedChampions = array();
                    $x = 0;
                    $duplicateBan = false;
                    foreach ($liveGameJSON->bannedChampions as $banned)
                    {
                        foreach ($bannedChampions as $checkBan)
                        {
                            if ($banned->championId == $checkBan)
                            {
                                $duplicateBan = true;
                            }
                        }
                        if ($duplicateBan == false)
                        {
                            $bannedChampions[$x] = $banned->championId;
                            $x++;
                        }
                        else if ($duplicateBan == true)
                        {
                            $duplicateBan = false;
                        }
                    }
                    echo "</table><p><b>Banned Champions: </b>";
                    foreach ($bannedChampions as $bannedChampion)
                    {
                        echo $championArray[$bannedChampion]."&emsp;";
                    }
                    echo "</p><table><tr><th>Red Team</th></tr>";
                    echo "<tr><td><h6>Summoner Name</h6></td><td><h6>Champion</h6></td></tr>";
                    foreach ($liveGameJSON->participants as $redSummoner)
                    {
                        if ($redSummoner->teamId == 200)
                        {
                            echo "<tr><td>".$redSummoner->summonerName."</td><td>".$championArray[$redSummoner->championId]."</td></tr>";
                        }
                    }
                    echo "</table>";
                }
                else
                {
                    echo "<h4>Summoner is not currently in a live game, or is in a game mode not supported by the Riot API.</h4>";
                }
            ?>
        </p>
    </body>
</html>