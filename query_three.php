<?php

    $DBhost = "localhost";
    $DBuser = "root";
    $DBName = "carsharing";
    $DBpassword = "";

    //inizio database
    require_once("database_lib.php");
    //connessione al database
    $db = new DB($DBhost, $DBName, $DBuser, $DBpassword);

    if( isset($_POST[id_autista]) &&!empty($_POST[id_autista]) ){
        
        $id_autista=$_POST['id_autista'];
        $query = "SELECT * FROM viaggio WHERE id_autista = $id_autista;";
        $elenco_viaggi = $db->query($query);
       
    }

    //recupero valori dalla form
    if( isset($_POST[id_viaggio]) &&!empty($_POST[id_viaggio]) && isset($_POST[voto_medio])&&!empty($_POST[voto_medio])  )
    {
        $id_viaggio=$_POST['id_viaggio']; 
        $voto_medio=$_POST['voto_medio']; 
        
        $query = "SELECT *
FROM  ( SELECT Passeggero.id_passeggero, Passeggero.Nominativo, AVG(feedbackNAut) AS votomedio
FROM Passeggero
INNER JOIN Prenotazione ON Passeggero.id_passeggero = Prenotazione.id_passeggero
WHERE Prenotazione.id_viaggio = $id_viaggio
GROUP BY Passeggero.id_passeggero, Passeggero.nominativo ) AS t
WHERE votomedio >= $voto_medio;";
        
        //eseguo la query
        $result = $db->query( $query );
        // disconnessione dal database
        //var_dump($result);
        $db = null;
        
    }

?>


<!DOCTYPE html>
<html>
<head>
    <script>
    </script>
    <title>Query database esame 2017</title>
</head>
<body>
    (terza query)<br>Valutazione caratteristiche passeggeri:<br>   
    <span><?php  if(isset($query)) echo "<br><br><b>Testo della query:<br></b>$query<br><br>"; ?></span>
<form action="query_three.php" method="post">
    
    Inserire id autista: <input <?php if(isset($id_autista)){ echo "value='$id_autista'";}?>placeholder='48'  type='text' id='id_autista' name='id_autista'>
     <input type='submit' value='Trova i suoi viaggi'>
 <?php       
        if(isset($elenco_viaggi) && !empty($elenco_viaggi))     
        {
?>
    
    <table id='table1' border>
    <caption><b>Viaggi dell'autista selezionato</b></caption>
    <thead>
    <tr>
        <th>id_viaggio</th>
        <th>partenza</th>
        <th>destinazione</th>
        <th>data</th> 
        <th>ora</th>  
        <th>modello auto</th> 
    </tr>
    </thead>
    <tbody>    
 <?php       
        
           foreach($elenco_viaggi as $row){
?>
    <tr>
    <td><?php echo ($row[ID_viaggio]); ?></td>
    <td><?php echo ($row[partenza]); ?></td>
    <td><?php echo ($row[destinazione]); ?></td>
    <td><?php echo ($row[data]); ?></td>
    <td><?php echo ($row[ora]); ?></td> 
    <td><?php echo ($row[auto]); ?></td>
    </tr>
        
<?php
     }
?>
        </tbody>
     </table>
 <br><br>
    Indicare id viaggio: 
    <select  id='id_viaggio' name='id_viaggio'>
      <option value='                    ' selected></option>";
        <?php 
            foreach($elenco_viaggi as $una){
            
        if($id_viaggio == $una[ID_viaggio])
            echo "<option value='$una[ID_viaggio]' selected>$una[ID_viaggio]</option>";
        else
            echo "<option value='$una[ID_viaggio]'>$una[ID_viaggio]</option>";
            }
        ?>
    </select>
    
    <br>Criterio di voto per selezione passeggeri [1-5]: <input <?php if(isset($voto_medio)){ echo "value='$voto_medio'";}?>placeholder='3'  type='text' id='voto_medio' name='voto_medio'><br>
    <input type='submit' value='Filtra i passeggeri'>
<?php
     
    }
    else
    {
        echo "<br>nessun viaggio è disponibile per questo autista.";
    }
   ?> 
    
    </form>
 <?php       
        if(isset($result))
        {
 
?>   
<br><br><table border>
    <caption><b>Passeggeri con feedback uguale o superiore a <?php if(isset($voto_medio)){ echo $voto_medio;}?></b></caption>
    <thead>
    <tr>
        <th>id_passeggero</th>
        <th>nominativo</th>
        <th>votomedio</th>  
    </tr>
    </thead>
    <tbody>    
 <?php       
        if(isset($result))
           foreach($result as $row){
 
?>
    <tr>
    <td><?php echo ($row['id_passeggero']); ?></td>
    <td><?php echo ($row['Nominativo']); ?></td>
    <td><?php echo ($row['votomedio']); ?></td>
        
<?php
        }
        echo "</tbody>\n";
        echo "</table>\n"; 
    }
?> 
    </tbody>
    </table>
    
    
</body>
</html>