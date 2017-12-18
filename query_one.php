<?php

    $DBhost = "localhost";
    $DBuser = "root";
    $DBName = "carsharing";
    $DBpassword = "";

    //inizio database
    require_once("database_lib.php");
    //connessione al database
    $db = new DB($DBhost, $DBName, $DBuser, $DBpassword);
    //query per riempire le select
    $partenze = $db->elenca_partenze();
    $destinazioni = $db->elenca_destinazioni();
    
    //recupero valori dalla form
    if( isset($_POST[data1]) &&!empty($_POST[data1]) && isset($_POST[data2])&&!empty($_POST[data2]) && isset($_POST[partenza])&&!empty($_POST[partenza]) && isset($_POST[destinazione])&&!empty($_POST[destinazione]) )
    {
        $data1=$_POST['data1']; 
        $data2=$_POST['data2']; 
        $partenza=$_POST['partenza']; 
        $destinazione=$_POST['destinazione'];
        
        $query = "SELECT autista.nominativo, autista.email, viaggio.partenza, viaggio.destinazione, viaggio.data, viaggio.ora, viaggio.tempo, viaggio.auto, viaggio.importo,
(viaggio.n_posti - viaggio.n_prenotazioni) AS posti_disponibili 
FROM autista 
INNER JOIN viaggio ON autista.id_autista = viaggio.id_autista 
WHERE viaggio.partenza='$partenza' AND viaggio.destinazione='$destinazione'
    AND viaggio.data BETWEEN '$data1' AND '$data2' AND (viaggio.n_posti - viaggio.n_prenotazioni)>0
ORDER BY viaggio.data,viaggio.ora";
        
        //eseguo la query
        $result = $db->query( $query );
        // disconnessione dal database
        $db = null;
        // controllo sul risultato dell'interrogazione
        if(count($result)==0)
        {
            //caso di righe = 0
            $messaggio="non sono disponibili viaggi per i parametri inseriti.";
        }else{
            //caso di risultato positivo
        }
    }

?>


<!DOCTYPE html>
<html>
<head>
    <script>
        function validateForm(){
            var a = document.getElementById("data1").value;
            var b = document.getElementById("data2").value;
            var c = document.getElementById("partenza").value;
            var d = document.getElementById("destinazione").value;
            if (a==null || a=="",b==null || b=="",c==null || c=="seleziona...",d==null || d=="seleziona...")
            {
                alert("Prego, inserire tutti i campi richiesti!");
                return false;
            }
        }
    </script>
    <title>Query database esame 2017</title>
</head>
<body>
    (prima query)<br>Ricerca di un viaggio disponibile: <br>
    
    
<form action="query_one.php" onsubmit="return validateForm()" method="post">
    Intervallo temporale: <br>
    Da:(inserire una data AAAA-MM-GG) <input placeholder='2017-01-01'  type='text' id='data1' name='data1'> <br>
     A:(inserire una data AAAA-MM-GG)<input placeholder='2019-01-01' type='text' id='data2' name='data2'> <br>
    Dettagli del viaggio:  <br>
    Origine: [demo: Bever Bievene]<select  id='partenza' name='partenza'>
      <option value="seleziona..." selected>seleziona...</option>
        <?php 
        foreach($partenze as $una_partenza){
        echo "<option value='$una_partenza[partenza]'>$una_partenza[partenza]</option>";
        }
        ?>
    </select><br>
    Destinazione: [demo: Carterton]<select  id='destinazione' name='destinazione'>
      <option value="seleziona..." selected>seleziona...</option>
        <?php 
        foreach($destinazioni as $una_dest){
        echo "<option value='$una_dest[destinazione]'>$una_dest[destinazione]</option>";
        }
        ?>
    </select>
    
    <input type='submit' value='Avvia ricerca'> 
</form>
    
<br>
    <span ><?php if(isset($messaggio))echo $messaggio; ?></span>
    <br>
<?php       
        if(isset($result))
        {
 
?>
    <span><?php  if(isset($query)) echo "<b>Testo della query:<br></b>$query<br><br>"; ?></span>
    <table border>
    <caption><b>Viaggi disponibili</b></caption>
    <thead>
    <tr>
        <th>Data</th>
        <th>Ora</th>
        <th>Partenza</th> 
        <th>Destinazione</th> 
        <th>Durata(minuti)</th> 
        <th>Nome autista</th> 
        <th>E-mail</th>
        <th>Auto</th>
        <th>Importo</th>
        <th>Posti disponibili</th>
    </tr>
    </thead>
    <tbody>    
 <?php       
        if(isset($result))
           foreach($result as $row){
 
?>
    <tr>
    <td><?php echo ($row['data']); ?></td>
    <td><?php echo ($row['ora']); ?></td>
    <td><?php echo ($row['partenza']); ?></td>
    <td><?php echo ($row['destinazione']); ?></td>
    <td><?php echo ($row['tempo']); ?></td>
    <td><?php echo ($row['nominativo']); ?></td> 
    <td><?php echo ($row['email']); ?></td>
    <td><?php echo ($row['auto']); ?></td>
    <td><?php echo ($row['importo']); ?></td>
    <td><?php echo ($row['posti_disponibili']); ?></td>
        
<?php
        }
        echo "</tbody>\n";
        echo "</table>\n"; 
?> 
    </tbody>
    </table>
<?php       
        }
        
 
?>    
   
</body>
</html>