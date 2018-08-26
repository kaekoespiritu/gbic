<?php
include_once('directives/db.php');
include('directives/session.php');

mysql_query('DELETE FROM absence_notif');
Print "	<script>
			window.location.assign('index.php')
		</script>";
?>