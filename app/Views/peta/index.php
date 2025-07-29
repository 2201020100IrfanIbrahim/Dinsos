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

    <label for="tingkat">Pilih Tingkat Wilayah:</label>
    <select id="tingkat">
      <option value="kecamatan">Kecamatan</option>
      <option value="kelurahan">Kelurahan</option>
    </select>

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
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([1.1, 104], 9);
    // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    // }).addTo(map);

    let geoLayer;
    const loading = document.getElementById('loading');
    const wilayahSelect = document.getElementById('wilayah');
    const tingkatSelect = document.getElementById('tingkat');

    function getColor(val) {
        // Saya sesuaikan sedikit agar cocok dengan legenda Anda
        return val > 100 ? '#e31a1c' :
               val > 50  ? '#fd8d3c' :
               val > 9   ? '#000000ff' : // Legenda Anda mulai dari 10, bukan 5
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

    function loadGeoJSON() {
        const wilayah = wilayahSelect.value;
        const tingkat = tingkatSelect.value;

        loading.style.display = 'inline';

        // GANTI URL agar sesuai dengan route baru: /peta/geojson/wilayah/tingkat
        const url = `<?= site_url('peta/geojson/') ?>${wilayah}/${tingkat}`;

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error(`Gagal memuat GeoJSON (${res.status})`);
                return res.json();
            })
            .then(data => {
                if (data.error) throw new Error(data.error); // Tangani error dari PHP
                
                if (geoLayer) map.removeLayer(geoLayer);

                geoLayer = L.geoJSON(data, {
                    style: styleFeature,
                    onEachFeature: function (feature, layer) {
                        const nama = feature.properties.NAMOBJ ?? 'Tidak diketahui';
                        const total = feature.properties.total_penerima ?? 0;
                        layer.bindPopup(`<strong>${nama}</strong><br>Total Penerima: ${total}`);
                    }
                }).addTo(map);

                // Hindari error jika data kosong
                if (geoLayer.getBounds().isValid()) {
                    map.fitBounds(geoLayer.getBounds());
                }
            })
            .catch(err => {
                console.error(err); // Tampilkan error di console untuk debug
                alert('Terjadi kesalahan: ' + err.message);
                if (geoLayer) map.removeLayer(geoLayer); // Bersihkan layer jika gagal
            })
            .finally(() => {
                loading.style.display = 'none';
            });
    }

    // Tambah legenda
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function () {
        const div = L.DomUtil.create('div', 'legend');
        // Sesuaikan grades agar cocok dengan fungsi getColor
        const grades = [0, 1, 10, 51, 101]; 
        const labels = [];

        div.innerHTML = '<strong>Total Penerima</strong><br>';
        
        for (let i = 0; i < grades.length; i++) {
            const from = grades[i];
            const to = grades[i + 1];

            labels.push(
                '<i style="background:' + getColor(from) + '"></i> ' +
                (from === 0 ? '0' : from) + (to ? '&ndash;' + (to - 1) : '+')
            );
        }

        div.innerHTML += labels.join('<br>');
        return div;
    };
    legend.addTo(map);

    // Perbaiki event listener dan pemanggilan awal
    wilayahSelect.addEventListener('change', loadGeoJSON);
    tingkatSelect.addEventListener('change', loadGeoJSON);

    // Panggil fungsi saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', loadGeoJSON);
</script>

</body>
</html>