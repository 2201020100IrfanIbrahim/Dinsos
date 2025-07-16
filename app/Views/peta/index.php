<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Peta Bantuan Sosial Kepri</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    body {
      font-family: sans-serif;
      margin: 20px;
    }

    #map {
      height: 600px;
      width: 100%;
      margin-top: 10px;
      background-color: #f9f9f9;
      border: 1px solid #ccc;
    }

    select {
      padding: 8px;
      font-size: 16px;
    }

    #loading {
      display: none;
      font-style: italic;
      margin-top: 10px;
      color: #555;
    }

    .legend {
      background: white;
      padding: 6px;
      line-height: 1.4em;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .legend i {
      width: 18px;
      height: 18px;
      float: left;
      margin-right: 8px;
      opacity: 0.8;
    }
  </style>
</head>
<body>

  <h2>Peta Sebaran Bantuan Sosial Kepri</h2>

  <label for="wilayah">Pilih Wilayah:</label>
  <select id="wilayah">
    <option value="karimun">Karimun</option>
    <option value="natuna">Natuna</option>
    <option value="bintan">Bintan</option>
    <option value="batam">Batam</option>
    <option value="lingga">Lingga</option>
    <option value="anambas">Anambas</option>
    <option value="tanjungpinang">Tanjung Pinang</option>
    <!-- Tambahkan opsi lainnya jika ada file GeoJSON -->
  </select>

  <div id="loading">Memuat data geojson...</div>
  <div id="map"></div>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    const map = L.map('map').setView([1.1, 104], 9);

    let geoLayer;
    const loading = document.getElementById('loading');

    function getColor(val) {
      return val > 100 ? '#e31a1c' :
             val > 50  ? '#fd8d3c' :
             val > 10  ? '#fecc5c' :
             val > 0   ? '#ffffb2' :
                         '#ccc';
    }

    function styleFeature(feature) {
      const val = feature.properties.total_penerima || 0;
      return {
        fillColor: getColor(val),
        color: "#333",
        weight: 1,
        fillOpacity: 0.8
      };
    }

    function loadGeoJSON(wilayah) {
      loading.style.display = 'inline';

      fetch(`<?= site_url('peta/geojson/') ?>${wilayah}`)
        .then(res => {
          if (!res.ok) throw new Error("Gagal memuat GeoJSON");
          return res.json();
        })
        .then(data => {
          if (geoLayer) map.removeLayer(geoLayer);

          geoLayer = L.geoJSON(data, {
            style: styleFeature,
            onEachFeature: function (feature, layer) {
              const nama = feature.properties.NAMOBJ ?? 'Tidak diketahui';
              const total = feature.properties.total_penerima ?? 0;
              layer.bindPopup(`<strong>${nama}</strong><br>Total Penerima: ${total}`);
            }
          }).addTo(map);

          map.fitBounds(geoLayer.getBounds());
        })
        .catch(err => {
          alert('Terjadi kesalahan: ' + err.message);
        })
        .finally(() => {
          loading.style.display = 'none';
        });
    }

    // Tambah legenda
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function () {
      const div = L.DomUtil.create('div', 'legend');
      const grades = [0, 1, 10, 50, 100];
      const labels = [];

      for (let i = 0; i < grades.length; i++) {
        const from = grades[i];
        const to = grades[i + 1];

        labels.push(
          `<i style="background:${getColor(from + 1)}"></i> ${from}${to ? '&ndash;' + (to - 1) : '+'}`
        );
      }

      div.innerHTML = `<strong>Keterangan:</strong><br>` + labels.join('<br>');
      return div;
    };
    legend.addTo(map);

    // Load default
    loadGeoJSON('karimun');

    document.getElementById('wilayah').addEventListener('change', function () {
      loadGeoJSON(this.value);
    });
  </script>

</body>
</html>
