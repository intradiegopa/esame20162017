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

     if( isset($_POST[id_autista]) &&!empty($_POST[id_autista]) 
        && isset($_POST[id_viaggio]) &&!empty($_POST[id_viaggio]) )
    {
        $id_autista=$_POST['id_autista'];
        $id_viaggio=$_POST['id_viaggio'];
         
        $query = "SELECT * FROM prenotazione WHERE id_viaggio = $id_viaggio AND esito=1;";
         
        $tab_prenotazioni = $db->query($query);
         
    }

    //recupero valori dalla form
    if( isset($_POST[id_autista]) &&!empty($_POST[id_autista]) && isset($_POST[id_viaggio]) &&!empty($_POST[id_viaggio]) && isset($_POST[id_prenotazione]) &&!empty($_POST[id_prenotazione]) )
    {
        $id_autista=$_POST['id_autista'];
        $id_viaggio=$_POST['id_viaggio'];
        $id_prenotazione=$_POST['id_prenotazione'];
        $u_query="select * from passeggero 
        inner join prenotazione on prenotazione.id_passeggero = passeggero.id_passeggero
        where id_prenotazione = $id_prenotazione AND prenotazione.esito =1";
        $dettagli_passeggero = $db->query_una_riga($u_query);
        
        $dettagli_viaggio = $db->query_una_riga("select * from viaggio where id_viaggio = $id_viaggio");
        
        $dettagli_autista = $db->query_una_riga("select * from autista where id_autista = $id_autista");
        
        $messaggio = "messaggio a: $dettagli_passeggero[nominativo] <br>
        email destinatario: $dettagli_passeggero[email]<br><br>
        
        testo email: <br> 
        Gentile $dettagli_passeggero[nominativo],
        la sua prenotazione per il viaggio da $dettagli_viaggio[partenza] a $dettagli_viaggio[destinazione] <br>
        da effettuarsi in data $dettagli_viaggio[data]<br> è stato confermata dall'autista $dettagli_autista[nominativo] con auto $dettagli_viaggio[auto].<br>
        La ringraziamo per aver utilizzato il nostro servizio di prenotazione.<br>
        Non esiti a porci i suoi dubbi o le sue domande a help@servizio_auto.it<br>
        Buona giornata.<br><br><br>";
        
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
    (seconda query)<br>Email di conferma prenotazione:<br>
    <span><?php  if(isset($u_query)) echo "<br><br><b>Testo della query:<br></b>$u_query<br><br>"; ?></span>
<form action="query_two.php" method="post">
    
    Inserire id autista: <input <?php if(isset($id_autista)){ echo "value='$id_autista'";}?>placeholder='67'  type='text' id='id_autista' name='id_autista'>
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
    
    
    <input type='submit' value='Trova le prenotazioni confermate'>
<?php
     }
    else
    {
        echo "<br>nessun viaggio è disponibile per questo autista.";
    }
    if(isset($tab_prenotazioni) && !empty($tab_prenotazioni))
    {
?>
    <table id='table2' border>
    <caption><b>Prenotazioni confermate</b></caption>
    <thead>
    <tr>
        <th>ID_prenotazione</th>
        <th>ID_passeggero</th>
        <th>data</th>
    </tr>
    </thead>
    <tbody>    
 <?php       
        
           foreach($tab_prenotazioni as $row){
?>
    <tr>
    <td><?php echo $row[ID_prenotazione]; ?></td>
    <td><?php echo $row[ID_passeggero]; ?></td>
    <td><?php echo $row[data]; ?></td>
    </tr>
        
<?php
     }
?>
        </tbody>
     </table>
 
    Indicare id prenotazione per produrre l'email: 

    <select  id='id_prenotazione' name='id_prenotazione'>
      <option value='                    ' selected></option>";
        <?php 
            foreach($tab_prenotazioni as $una){
            
        if($id_prenotazione == $una[ID_prenotazione])
            echo "<option value='$una[ID_prenotazione]' selected>$una[ID_prenotazione]</option>";
        else
            echo "<option value='$una[ID_prenotazione]'>$una[ID_prenotazione]</option>";
            }
        ?>
    </select>
    
    <input type='submit' value='Stampa email precompilata'>
<?php
     }
    else
    {
        echo "<br>nessuna prenotazione è stata effettuata";
    }
?>
    
</form>
    
<?php
    
     if(isset($messaggio))
     {
         
         echo "<br><br><br> Email: <br><br>";
         echo $messaggio;
     }
?>
    

</body>
</html>