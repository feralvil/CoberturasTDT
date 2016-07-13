<h1><?php echo __('Mapa de Centro TDT de la Generalitat');?></h1>
<h2><?php echo __('Ubicación del Centro') . ' ' . $centro['Centro']['centro'];?></h2>
<table class="ink-table bordered hover alternating">
    <tr>
        <th><?php echo __('Longitud');?></th>
        <th><?php echo __('Latitud');?></th>
        <th><?php echo __('UTMX');?></th>
        <th><?php echo __('UTMY');?></th>
    </tr>
    <tr>
        <td><?php echo $centro['Centro']['utmx']; ?></td>
        <td><?php echo $centro['Centro']['utmy']; ?></td>
        <td><?php echo $centro['Centro']['longitud']; ?></td>
        <td><?php echo $centro['Centro']['latitud']; ?></td>
    </tr>
</table>
<div id="map" style="height: 400px;"></div>
<script type="text/javascript">
var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 8
  });
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDczQVENK_qWVMJCfca_4500HSqjIZ56n8&callback=initMap">
</script>
