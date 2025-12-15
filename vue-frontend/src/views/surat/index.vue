<template>
  <div class="page-inner">
    <h1 class="title">{{ pageTitle }}</h1>

    <div class="info-container">
      <div class="info">
        <p class="info-label">NIM</p>
        <p class="info-value">{{ mahasiswa.nim }}</p>
      </div>
      <div class="info">
        <p class="info-label">Nama</p>
        <p class="info-value">{{ mahasiswa.nama }}</p>
      </div>
      <div class="info">
        <p class="info-label">Fakultas</p>
        <p class="info-value">{{ mahasiswa.fakultas }}</p>
      </div>
      <div class="info">
        <p class="info-label">Program Studi</p>
        <p class="info-value">{{ mahasiswa.prodi }}</p>
      </div>
      <div class="info">
        <p class="info-label">Status</p>
        <p class="info-value status">{{ mahasiswa.status }}</p>
      </div>
    </div>

    <section class="stats">
      <div class="stats-card">
        <div class="stats-label">Total Cuti</div>
        <div class="stats-value">{{ jumlahCuti }}</div>
      </div>
      <div class="stats-card">
        <div class="stats-label">Total Aktif Kembali</div>
        <div class="stats-value">{{ jumlahAktif }}</div>
      </div>
    </section>

    <LayananHome
      v-if="view === 'home'"
      @go-cuti="goToCuti"
      @go-aktif="goToAktif"
    />

    <!-- RIWAYAT CUTI (plain table, bukan tombol) -->
    <section v-else-if="view === 'cuti'" class="card">
      <div class="table-wrapper">
        <div class="table-header-top">
          <div class="table-title">Riwayat Cuti</div>
          <input class="search-input" v-model="searchCuti" placeholder="Cari tahun/semester..." />
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tahun Ajar</th>
              <th>Semester Ajar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in filteredCuti" :key="item.id" class="table-row">
              <td>{{ idx + 1 }}</td>
              <td>{{ item.tahun_ajar }}</td>
              <td>{{ item.semester_ajar }}</td>
            </tr>
            <tr v-if="filteredCuti.length === 0">
              <td colspan="3" class="empty-cell">Data tidak ditemukan</td>
            </tr>
          </tbody>
        </table>

        <button class="back-button-bottom" @click="goHome">← Kembali</button>
      </div>
    </section>

    <!-- RIWAYAT AKTIF KEMBALI (plain table) -->
    <section v-else-if="view === 'aktif'" class="card">
      <div class="table-wrapper">
        <div class="table-header-top">
          <div class="table-title">Riwayat Aktif Kembali</div>
          <input class="search-input" v-model="searchAktif" placeholder="Cari tahun/semester..." />
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tahun Ajar</th>
              <th>Semester Ajar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in filteredAktif" :key="item.id" class="table-row">
              <td>{{ idx + 1 }}</td>
              <td>{{ item.tahun_ajar }}</td>
              <td>{{ item.semester_ajar }}</td>
            </tr>
            <tr v-if="filteredAktif.length === 0">
              <td colspan="3" class="empty-cell">Data tidak ditemukan</td>
            </tr>
          </tbody>
        </table>

        <button class="back-button-bottom" @click="goHome">← Kembali</button>
      </div>
    </section>

    <section v-if="showHelp" class="card help-card">
      <div class="help-title">Bantuan Layanan</div>
      <p class="help-text">
        • Pilih menu <b>Cuti</b> atau <b>Aktif Kembali</b> untuk melihat
        riwayat.<br />
        • Gunakan kolom pencarian untuk menyaring data.<br />
        • Klik baris tabel untuk melihat detail riwayat.
      </p>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "../../api.js";
import LayananHome from "../../components/LayananHome.vue";

const route = useRoute();
const mahasiswaId = route.params.id || 1; // fallback id

const view = ref("home");
const showHelp = ref(false);

const mahasiswa = ref({
  nama: "Ferdi Naufal Prasetyo",
  nim: "202210370311272",
  fakultas: "Teknik",
  prodi: "Program Studi Informatika",
  status: "aktif",
});

// tabel data & default fallback
const tabelCuti = ref([]);
const tabelAktif = ref([]);

const defaultCuti = [
  { id: 1, tahun_ajar: "2023", semester_ajar: "2" },
  { id: 2, tahun_ajar: "2024", semester_ajar: "3" },
];
const defaultAktif = [{ id: 1, tahun_ajar: "2024", semester_ajar: "4" }];

const searchCuti = ref("");
const searchAktif = ref("");

const filteredCuti = computed(() => {
  const q = searchCuti.value.trim().toLowerCase();
  const list = tabelCuti.value.length ? tabelCuti.value : defaultCuti;
  if (!q) return list;
  return list.filter(
    (i) =>
      String(i.tahun_ajar).toLowerCase().includes(q) ||
      String(i.semester_ajar).toLowerCase().includes(q)
  );
});

const filteredAktif = computed(() => {
  const q = searchAktif.value.trim().toLowerCase();
  const list = tabelAktif.value.length ? tabelAktif.value : defaultAktif;
  if (!q) return list;
  return list.filter(
    (i) =>
      String(i.tahun_ajar).toLowerCase().includes(q) ||
      String(i.semester_ajar).toLowerCase().includes(q)
  );
});

const fetchMahasiswa = async () => {
  try {
    const res = await api.get(`/mahasiswa/${mahasiswaId}`);
    const m = res.data || {};
    mahasiswa.value = {
      nama: m.nama ?? m.name ?? mahasiswa.value.nama,
      nim: m.nim ?? m.nim_mahasiswa ?? mahasiswa.value.nim,
      fakultas: m.fakultas ?? m.faculty ?? mahasiswa.value.fakultas,
      prodi: m.prodi ?? m.program_studi ?? mahasiswa.value.prodi,
      status: m.status ?? mahasiswa.value.status,
    };
  } catch (e) {
    console.error("fetchMahasiswa error", e);
  }
};

const fetchPengajuanSurat = async () => {
  try {
    const res = await api.get("/pengajuan-surat", {
      params: { mahasiswa_id: mahasiswaId },
    });
    const data = res.data || [];
    const cuti = data.filter((i) =>
      i.jenis_surat?.nama_surat?.toLowerCase().includes("cuti")
    );
    const aktif = data.filter(
      (i) => !i.jenis_surat?.nama_surat?.toLowerCase().includes("cuti")
    );

    tabelCuti.value = cuti.length ? cuti : defaultCuti;
    tabelAktif.value = aktif.length ? aktif : defaultAktif;
  } catch (e) {
    console.error("fetchPengajuanSurat error", e);
    tabelCuti.value = defaultCuti;
    tabelAktif.value = defaultAktif;
  }
};

onMounted(() => {
  fetchMahasiswa();
  fetchPengajuanSurat();
});

const jumlahCuti = computed(() => (tabelCuti.value.length ? tabelCuti.value.length : defaultCuti.length));
const jumlahAktif = computed(() => (tabelAktif.value.length ? tabelAktif.value.length : defaultAktif.length));

const pageTitle = computed(() => {
  if (view.value === "cuti") return "Cuti";
  if (view.value === "aktif") return "Aktif Kembali";
  return "Layanan Mandiri";
});

const goHome = () => (view.value = "home");
const goToCuti = () => (view.value = "cuti");
const goToAktif = () => (view.value = "aktif");
</script>

<style>
/* ...existing styles... */
</style>

<style>
.page {
  min-height: auto;
  width: 100%;
  box-sizing: border-box;
  padding: 24px 16px 40px;
  display: flex;
  justify-content: center;
}

.page-inner {
  max-width: 100%;
  margin: 0 auto;
  background: #ffffff;
  border-radius: 25px;
  padding: 20px 16px 28px;
  box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
  transition: box-shadow 0.25s ease, transform 0.25s ease;
  margin: 0;
}

.page-inner:hover {
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15), 0 20px 45px rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
}

@media (min-width: 768px) {
  .page-inner {
    max-width: 100%;
    padding: 24px 24px 32px;
  }
}

@media (min-width: 1200px) {
  .page-inner {
    max-width: 100%;
  }
}

.title {
  font-size: 28px;
  font-weight: 700;
  color: #555;
  margin-bottom: 24px;
}

.info-container {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 24px;
}

.info-label {
  font-size: 13px;
  color: #6b7280;
  margin: 0;
}

.info-value {
  font-size: 14px;
  color: #111827;
  margin: 0;
}

.info-value.status {
  font-weight: 600;
  color: #2e7d32;
}

.stats {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 10px;
  margin-bottom: 18px;
}

.stats-card {
  padding: 10px 12px;
  border-radius: 10px;
  background: linear-gradient(135deg, #e3f2fd, #ffffff);
  border: 1px solid #d0e3ff;
}

.stats-label {
  font-size: 12px;
  color: #555555;
  margin-bottom: 4px;
}

.stats-value {
  font-size: 18px;
  font-weight: 600;
  color: #1976d2;
}

.card {
  border-radius: 10px;
  border: 1px solid #e5e5e5;
  background: #ffffff;
  overflow: hidden;
  margin-bottom: 16px;
}

.layanan-list {
  display: flex;
  flex-direction: column;
}

.layanan-item {
  width: 100%;
  padding: 12px 14px;
  border: none;
  border-bottom: 1px solid #f0f0f0;
  font-size: 14px;
  background: #ffffff;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background-color 0.15s ease, transform 0.15s ease,
    box-shadow 0.15s ease;
}

.layanan-item:last-child {
  border-bottom: none;
}

.layanan-item:hover {
  background: #f5f9ff;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(25, 118, 210, 0.18);
}

.chevron {
  color: #999999;
}

.table-wrapper {
  padding: 12px 12px 18px;
  position: relative;
}

.table-header-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 10px;
}

.table-title {
  font-size: 14px;
  font-weight: 600;
  color: #333333;
}

.table-toolbar {
  flex-shrink: 0;
}

.search-input {
  padding: 6px 10px;
  border-radius: 16px;
  border: 1px solid #d0d0d0;
  font-size: 12px;
  min-width: 150px;
}

.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12px;
}

.table th,
.table td {
  padding: 8px 10px;
}

.table thead tr {
  border-bottom: 1px solid #e5e5e5;
  background: #fafafa;
}

.table-row + .table-row {
  border-top: 1px solid #f1f1f1;
}

.table-row:hover {
  background: #f5f5f5;
  cursor: pointer;
}

.row-selected {
  background: #e3f2fd;
}

.empty-cell {
  text-align: center;
  padding: 10px;
  color: #999999;
}

.detail-box {
  margin-top: 14px;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #d0e3ff;
  background: #f5f9ff;
  font-size: 12px;
}

.detail-title {
  font-weight: 600;
  margin-bottom: 6px;
  color: #1a3d7c;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 4px;
}

.detail-row span:first-child {
  color: #555555;
}

.detail-row span:last-child {
  font-weight: 500;
}

.back-button-bottom {
  margin-top: 14px;
  border-radius: 20px;
  border: 1px solid #1976d2;
  background: #e3f2fd;
  font-size: 13px;
  cursor: pointer;
  padding: 6px 14px;
  color: #1976d2;
  transition: background-color 0.18s ease, transform 0.18s ease,
    box-shadow 0.18s ease;
}

.back-button-bottom:hover {
  background: #bbdefb;
  transform: translateY(-1px);
  box-shadow: 0 2px 6px rgba(25, 118, 210, 0.3);
}

.help-card {
  position: fixed;
  right: 24px;
  bottom: 90px;
  width: 260px;
  max-width: calc(100% - 48px);
  padding: 12px 14px;
  border-radius: 12px;
  font-size: 12px;
}

.help-title {
  font-weight: 600;
  margin-bottom: 6px;
}

.help-text {
  line-height: 1.4;
}
</style>