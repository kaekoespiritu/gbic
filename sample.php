<form method="post">
<?php
for ($i=1; $i <5 ; $i++) 
{ 
    echo'<input type="checkbox" value="'.$i.'" name="checkbox[]"/>';
} 
?>
<input type="submit" name="submit" class="form-control" value="Submit">  
</form>

<?php 
if (isset($_POST['submit'])) {

for($b = 0; $b<= 5; $b++){
    if(isset($_POST['checkbox'][$b]))
        $book .= ' '.$_POST['checkbox'][$b];
}
Print $book;
}
?>