<x-app-layout>
    <x-slot name="header">
      <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>
  
    <div class="p-6">
      <h2 class="text-xl font-bold mb-4">Data Sekolah</h2>
      <button onclick="showModal()" class="bg-blue-500 text-dark px-4 py-2 mb-4 rounded">Tambah Data</button>
  
      <table class="table-auto w-full border text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-2 py-1">#</th>
            <th class="border px-2 py-1">Nama</th>
            <th class="border px-2 py-1">Alamat</th>
            <th class="border px-2 py-1">Telepon</th>
            <th class="border px-2 py-1">Email</th>
            <th class="border px-2 py-1">Jenis Sekolah</th>
            <th class="border px-2 py-1">Status Sekolah</th>
            <th class="border px-2 py-1">Akreditasi</th>
            <th class="border px-2 py-1">Website</th>
            <th class="border px-2 py-1">Latitude</th>
            <th class="border px-2 py-1">Longitude</th>
            <th class="border px-2 py-1">Aksi</th>
          </tr>
        </thead>
        <tbody id="data-sekolah">
          <tr><td colspan="12" class="text-center p-4">Memuat data...</td></tr>
        </tbody>
      </table>
    </div>
  
    <!-- Modal Form Tambah/Edit Sekolah -->
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
  
    
    <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
    async function loadSekolahData() {
      try {
        const res = await fetch('/api/sekolah', {
          headers: { 'Accept': 'application/json' }
        });
        const response = await res.json();
        const data = response.data.data;
  
        let html = '';
        data.forEach((item, index) => {
          const encodedData = btoa(JSON.stringify(item));
          html += `
            <tr>
              <td class="border px-2 py-1">${index + 1}</td>
              <td class="border px-2 py-1">${item.nama}</td>
              <td class="border px-2 py-1">${item.alamat}</td>
              <td class="border px-2 py-1">${item.telepon}</td>
              <td class="border px-2 py-1">${item.email}</td>
              <td class="border px-2 py-1">${item.jenis_sekolah}</td>
              <td class="border px-2 py-1">${item.status_sekolah}</td>
              <td class="border px-2 py-1">${item.akreditasi}</td>
              <td class="border px-2 py-1">${item.website ?? '-'}</td>
              <td class="border px-2 py-1">${item.latitude ?? '-'}</td>
              <td class="border px-2 py-1">${item.longitude ?? '-'}</td>
              <td class="border px-2 py-1">
                <button onclick="editSekolah('${encodedData}')" class="bg-yellow-500 text-dark px-2 py-1 rounded">Edit</button>
                <button onclick="hapusSekolah(${item.id})" class="bg-red-500 text-dark px-2 py-1 rounded">Hapus</button>
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
      document.getElementById('telepon').value = data.telepon;
      document.getElementById('email').value = data.email;
      document.getElementById('jenis_sekolah').value = data.jenis_sekolah;
      document.getElementById('status_sekolah').value = data.status_sekolah;
      document.getElementById('akreditasi').value = data.akreditasi;
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
  
      const url = isEdit ? `/api/sekolah/${id}` : `/api/sekolah`;
      const method = isEdit ? 'PUT' : 'POST';
  
      try {
        const res = await fetch(url, {
          method,
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
          const res = await fetch(`/api/sekolah/${id}`, {
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
  
    // Load data saat halaman pertama kali dibuka
    loadSekolahData();
  </script>
  
  </x-app-layout>