<?php
//Check user authentication
include $_SERVER['DOCUMENT_ROOT'] . '/../server/accesscontrol.php';

$result = json_decode($_REQUEST['msg'], true);
?>

<div  align="center">
<table border="1">
    <tr>
        <th width="200px">Unit</th>
        <th width="200px">Date</th>
        <th width="200px">Command</th>
        <th width="500px">Description</th>
        <th width="100px">Type</th>
    </tr>
<?php
foreach ($result as $element) {
?>
    <tr>
        <td><?php echo $element['iridium_unit'];?></td>
        <td><?=$element['date_sent'];?></td>
        <td><?=$element['command'];?></td>
        <td><?=$element['description'];?></td>
        <td><?=$element['type'];?></td>
    </tr>
<?php
}
?>

</table>
</div>