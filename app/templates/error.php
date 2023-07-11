<?php ob_start() ?>

<?php $contenido = ob_get_clean()?>
<h1> ERROR</h1>
<h3>
<?php  
echo $mensaje;?>
</h3>



