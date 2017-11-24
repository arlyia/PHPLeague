<?php

//array to store the currently used game mode ids as keys and their names as values
$matchTypes =
[
    0 => "Custom Game",
    70 => "One for All",
    72 => "1v1 Snowdown Showdown",
    73 => "2v2 Snowdown Showdown",
    75 => "6v6 Hexakill",
    76 => "Ultra Rapid Fire",
    78 => "One for All (Mirror)",
    83 => "Co-op vs AI Ultra Rapid Fire",
    96 => "Ascension",
    98 => "Hexakill",
    100 => "ARAM",//Butcher's Bridge
    300 => "Poro King",
    310 => "Nemesis",
    313 => "Black Market Brawlers",
    317 => "Definitely Not Dominion",
    318 => "ARURF",
    325 => "All Random",//SR
    400 => "Normal Draft 5v5",
    420 => "Ranked Solo/Duo Draft",
    430 => "Normal Blind 5v5",
    440 => "Ranked Flex 5v5 Draft",
    450 => "ARAM",
    460 => "Normal Blind 3v3",
    470 => "Ranked Flex 3v3",
    600 => "Blood Hunt",
    610 => "Dark Star",
    800 => "Co-op vs AI 3v3 Intermediate",
    810 => "Co-op vs AI 3v3 Introduction",
    820 => "Co-op vs AI 3v3 Beginner",
    830 => "Co-op vs AI 5v5 Introduction",
    840 => "Co-op vs AI 5v5 Beginner",
    850 => "Co-op vs AI 5v5 Intermediate",
    940 => "Nexus Siege",
    950 => "Level 100 DOOM Bots",
    960 => "The Teemoing DOOM Bots",
    980 => "Star Guardian Invasion: Normal",
    990 => "Star Guardian Invasion: Onslaught"
];

$mapTypes = 
[
    1 => "Summoner's Rift (Classic)",
    2 => "Summoner's Rift (Classic-Fall)",
    3 => "The Proving Grounds",
    4 => "Twisted Treeline (Classic)",
    8 => "The Crystal Scar",
    10 => "Twisted Treeline",
    11 => "Summoner's Rift",
    12 => "Howling Abyss",
    14 => "Butcher's Bridge",
    16 => "Cosmic Ruins",
    18 => "Valoran City Park"
];

//$jJSON is the live game json from the API
//aMatchTypes and $aMapTypes are arrays from this file with mode and map information
//returns the name of the game mode and the map it's played on
function GetMapAndMode($jJSON, $aMatchTypes, $aMapTypes)
{
    $gameInfo = array("modeName" => "", "mapName" => "");
    
    if ($jJSON->gameType == "CUSTOM_GAME")
    {
        $x = 0;
    }
    else
    {
        $x = $jJSON->gameQueueConfigId;
    }
    
    $y = $jJSON->mapId;
    
    $gameInfo["modeName"] = $aMatchTypes[$x];
    $gameInfo["mapName"] = $aMapTypes[$y];
    
    return $gameInfo;
}

?>