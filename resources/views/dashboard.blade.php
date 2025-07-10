<x-app-layout>
    <x-slot name="header">
      <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>
  
  <div class="p-6">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">📚 Data Sekolah</h2>
    <button onclick="showModal()" class="bg-white text-black px-5 py-2 rounded hover:bg-blue-700 transition">
      + Tambah Data
    </button>
  </div>
  <div class="overflow-x-auto rounded-lg shadow">
    <table class="w-full text-sm text-left text-gray-700">
      <thead class="bg-blue-50 text-gray-700 uppercase">
        <tr>
          <th class="px-4 py-3 border">#</th>
          <th class="px-4 py-3 border">Nama</th>
          <th class="px-4 py-3 border">Alamat</th>
          <th class="px-4 py-3 border">Telepon</th>
          <th class="px-4 py-3 border">Email</th>
          <th class="px-4 py-3 border">Jenis</th>
          <th class="px-4 py-3 border">Status</th>
          <th class="px-4 py-3 border">Akreditasi</th>
          <th class="px-4 py-3 border">Website</th>
          <th class="px-4 py-3 border">Latitude</th>
          <th class="px-4 py-3 border">Longitude</th>
          <th class="px-4 py-3 border">Aksi</th>
        </tr>
      </thead>
      <tbody id="data-sekolah" class="bg-white">
        <tr>
          <td colspan="12" class="text-center px-4 py-6 text-gray-500">Memuat data...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah -->
 <div id="modal-form" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 relative">
        <h2 id="modal-title" class="text-xl font-bold mb-4">Tambah Sekolah</h2>
        <form id="form-sekolah" onsubmit="submitSekolah(event)">
          <input type="hidden" name="id" id="sekolah-id">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="nama" id="nama" class="border p-2 rounded" placeholder="Nama Sekolah" required>
            <input type="text" name="alamat" id="alamat" class="border p-2 rounded" placeholder="Alamat" required>
            <input type="text" name="telepon" id="telepon" class="border p-2 rounded" placeholder="Telepon" required>
            <input type="email" name="email" id="email" class="border p-2 rounded" placeholder="Email" required>
            <input type="text" name="jenis_sekolah" id="jenis_sekolah" class="border p-2 rounded" placeholder="Jenis Sekolah" required>
            <input type="text" name="status_sekolah" id="status_sekolah" class="border p-2 rounded" placeholder="Status Sekolah" required>
            <input type="text" name="akreditasi" id="akreditasi" class="border p-2 rounded" placeholder="Akreditasi" required>
            <input type="url" name="website" id="website" class="border p-2 rounded" placeholder="Website">
            <input type="text" name="latitude" id="latitude" class="border p-2 rounded" placeholder="Latitude">
            <input type="text" name="longitude" id="longitude" class="border p-2 rounded" placeholder="Longitude">
          </div>
          <div class="mt-6 flex justify-end space-x-2">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-dark rounded">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-dark rounded">Simpan</button>
          </div>
        </form>
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl">&times;</button>
      </div>
    </div>

<style>
  /* Optional: Untuk membuat form lebih smooth saat scroll */
#modal-form::-webkit-scrollbar {
  width: 8px;
}

#modal-form::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

#modal-form::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

#modal-form::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>


  
    
    <script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function loadSekolahData() {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/sekolah', {
      headers: { 'Accept': 'application/json' }
    });
    const resJson = await res.json();
    const data = resJson.data.data; // <-- Fix disini!

    let html = '';
    data.forEach((item, index) => {
      const encodedData = btoa(JSON.stringify(item));
      html += `
        <tr>
          <td class="border px-2 py-1">${index + 1}</td>
          <td class="border px-2 py-1">${item.nama}</td>
          <td class="border px-2 py-1">${item.alamat}</td>
          <td class="border px-2 py-1">${item.telepon ?? '-'}</td>
          <td class="border px-2 py-1">${item.email ?? '-'}</td>
          <td class="border px-2 py-1">${item.jenis_sekolah ?? '-'}</td>
          <td class="border px-2 py-1">${item.status_sekolah ?? '-'}</td>
          <td class="border px-2 py-1">${item.akreditasi ?? '-'}</td>
          <td class="border px-2 py-1">${item.website ?? '-'}</td>
          <td class="border px-2 py-1">${item.latitude ?? '-'}</td>
          <td class="border px-2 py-1">${item.longitude ?? '-'}</td>
          <td class="border px-2 py-1 text-center space-x-2">
  <!-- Tombol Edit -->
  <button onclick='editSekolah("${encodedData}")' class="text-yellow-500 hover:text-yellow-600 transition">
    <!-- Ikon Pensil SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l3 3 9-9-3-3-9 9zm0 0H5v4h4v-4z" />
    </svg>
  </button>

  <!-- Tombol Hapus -->
  <button onclick="hapusSekolah(${item.id})" class="text-red-500 hover:text-red-600 transition" title="Hapus">
    <!-- Ikon Tempat Sampah SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v2H9V4a1 1 0 011-1z" />
    </svg>
  </button>
</td>

        </tr>`;
    });

    document.getElementById('data-sekolah').innerHTML = html;
  } catch (error) {
    document.getElementById('data-sekolah').innerHTML = `
      <tr><td colspan="12" class="text-red-500 text-center p-4">Gagal memuat data: ${error.message}</td></tr>`;
  }
}


function showModal(isEdit = false) {
  document.getElementById('modal-form').classList.remove('hidden');
  document.getElementById('modal-title').textContent = isEdit ? 'Edit Sekolah' : 'Tambah Sekolah';
}

function closeModal() {
  document.getElementById('modal-form').classList.add('hidden');
  document.getElementById('form-sekolah').reset();
  document.getElementById('sekolah-id').value = '';
}

function editSekolah(encodedData) {
  const data = JSON.parse(atob(encodedData));
  document.getElementById('sekolah-id').value = data.id;
  document.getElementById('nama').value = data.nama;
  document.getElementById('alamat').value = data.alamat;
  document.getElementById('telepon').value = data.telepon ?? '';
  document.getElementById('email').value = data.email ?? '';
  document.getElementById('jenis_sekolah').value = data.jenis_sekolah ?? '';
  document.getElementById('status_sekolah').value = data.status_sekolah ?? '';
  document.getElementById('akreditasi').value = data.akreditasi ?? '';
  document.getElementById('website').value = data.website ?? '';
  document.getElementById('latitude').value = data.latitude ?? '';
  document.getElementById('longitude').value = data.longitude ?? '';
  showModal(true);
}

async function submitSekolah(event) {
  event.preventDefault();
  const form = document.getElementById('form-sekolah');
  const formData = new FormData(form);
  const id = formData.get('id');
  const isEdit = !!id;

  const payload = {
    nama: formData.get('nama'),
    alamat: formData.get('alamat'),
    telepon: formData.get('telepon'),
    email: formData.get('email'),
    jenis_sekolah: formData.get('jenis_sekolah'),
    status_sekolah: formData.get('status_sekolah'),
    akreditasi: formData.get('akreditasi'),
    website: formData.get('website') || null,
    latitude: formData.get('latitude') || null,
    longitude: formData.get('longitude') || null
  };

  const url = isEdit
    ? `http://127.0.0.1:8000/api/sekolah/${id}`
    : `http://127.0.0.1:8000/api/sekolah`;

  const method = isEdit ? 'PUT' : 'POST';

  try {
    const res = await fetch(url, {
      method: method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(payload)
    });

    if (res.ok) {
      closeModal();
      loadSekolahData();
    } else {
      const err = await res.json();
      alert('Gagal menyimpan data: ' + (err.message || 'Periksa isian'));
    }
  } catch (error) {
    console.error(error);
    alert('Terjadi kesalahan saat menyimpan data.');
  }
}

async function hapusSekolah(id) {
  if (confirm('Yakin ingin menghapus data ini?')) {
    try {
      const res = await fetch(`http://127.0.0.1:8000/api/sekolah/${id}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      });
      if (res.ok) {
        loadSekolahData();
      } else {
        alert('Gagal menghapus data');
      }
    } catch (error) {
      console.error(error);
      alert('Terjadi kesalahan saat menghapus data.');
    }
  }
}

loadSekolahData();
</script>

  
  </x-app-layout>