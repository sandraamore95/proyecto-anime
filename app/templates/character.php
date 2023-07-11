<?php include "parte_superior.php"; ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<style>
    .table-image td,
    th {
        vertical-align: middle;
    }
    body{
    
     color:black !important;
    }
  .rec-list {
    display: flex;
    overflow: auto hidden;
  }
</style>
<div class="container mt-4">
    <h1><?php echo $nick; ?></h1>
    <div>
        <img style="float:left;margin:0 15px 10px 0" src=<?php echo $imagen; ?> alt="">
        <p><?php echo $about; ?></p>
        <h3>Other Animes ... </h3>
        <div class="rec-list">
        <?php 
         //relaciones 
         $anime_relationship = $json['data']['anime'];
         for ($i = 0; $i < count($anime_relationship); $i++) {
             $id = $anime_relationship[$i]['anime']['mal_id'];
             $img = $anime_relationship[$i]['anime']['images']['webp']['image_url'];
             echo '<a href="index.php?ctl=anime&var1='.$id.'"><img height=80px; style="margin:5px;"   src=' . $img. ' alt=""></a>';
         }
        ?>
        </div>
    </div>
</div>

<div class="container mt-4">

    <table class="table table-image">
   
        <thead>
            <tr>
                <th scope="col float-start ">Image</th>
                <th>Language</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php $i = 0;
                foreach ($person_voice as $person) : ?>
            <tr></tr>
            <td class="w-25" valign="top" width="46"><img class="img-fluid img-responsive" src="<?php echo $person['person']['images']['jpg']['image_url'] ?>" /></td>

            <td><?php echo $person['language']  ?></td>
            <td><?php echo $person['person']['name']  ?></td>
            </tr>
            <tr>
            <?php endforeach ?>
            </tr>
    </table>
    </div>

<?php include "parte_inferior.php"; ?>