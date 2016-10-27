<h1><?php echo __('Ubicación de Centro TDT de la Generalitat');?></h1>

<div class="column-group gutters">
    <div id="map" class="map large-60"></div>
    <div class="large-40">
        <h4><?php echo __('Ubicación del Centro') . ' ' . $centro['Centro']['centro'];?></h4>
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
    </div>
</div>
<script type="text/javascript">
    var centro = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([<?php echo $centro['Centro']['longitud'] . ', ' . $centro['Centro']['latitud'];?>]))
    });

    centro.setStyle(new ol.style.Style({
        image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            src: '../../img/marcador.png'
        }))
    }));

    var vectorSource = new ol.source.Vector({
       features: [centro]
     });

     var vectorLayer = new ol.layer.Vector({
       source: vectorSource,
     });

     var rasterLayer = new ol.layer.Tile({source: new ol.source.OSM()});

    var map = new ol.Map({
        target: 'map',
        layers: [rasterLayer, vectorLayer],
        view: new ol.View({
          center: ol.proj.fromLonLat([
              <?php echo $centro['Centro']['longitud'] . ', ' . $centro['Centro']['latitud'];?>]
          ),
          zoom: 10
        })
      });
</script>
