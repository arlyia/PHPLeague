<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>League of Legends Live Game Lookup</title>
        
    </head>
    <body>
        <p>Please insert your summoner name and region</p>
        <form action="summoner.php" method="get">
            <h2>Summoner Name:</h2>
            <input type="text" autofocus="true" max="15" name="summonerName" pattern="[a-zA-Z0-9\\p{L} _\\.]+" title="Visible Unicode Letter Characters, Digits (0-9), '_', '.', ' '" required> </input>
            <h2>Server Region</h2>
            <input type="radio" name="region" value="euw1" checked>Europe West<br />
            <input type="radio" name="region" value="eun1">Europe Nordic and East<br />
            <input type="radio" name="region" value="na1">North America<br />
            <input type="radio" name="region" value="jp1">Japan<br />
            <input type="radio" name="region" value="kr">Korea<br />
            <input type="radio" name="region" value="la1">Latin America North<br />
            <input type="radio" name="region" value="la2">Latin America South<br />
            <input type="radio" name="region" value="br1">Brazil<br />
            <input type="radio" name="region" value="oc1">Oceania<br />
            <input type="radio" name="region" value="tr1">Turkey<br />
            <input type="radio" name="region" value="ru">Russia<br />
            <input type="radio" name="region" value="pbe1">PBE<br />
            <br />
            <input type="submit" value="Submit" />
        </form>
    </body>
</html>
