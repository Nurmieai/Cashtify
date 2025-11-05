<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kasir - Cashtify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">
        <img src="https://cdn-icons-png.flaticon.com/512/3566/3566340.png" alt="Logo" width="25" height="25" class="me-2">
        Cashtify
      </a>
    </div>
  </nav>

  <!-- Form Input Barang -->
  <div class="container my-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="text-center mb-4">Form Input Barang</h4>

        <form class="row g-3 justify-content-center">
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Nama Barang" required>
          </div>
          <div class="col-md-3">
            <input type="number" class="form-control" placeholder="Harga (Rp)" required>
          </div>
          <div class="col-md-2">
            <input type="number" class="form-control" placeholder="Jumlah" required>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-success w-100 fw-semibold">Tambah ke Daftar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Daftar Barang -->
  <div class="container mb-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Daftar Barang</h5>

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead class="table-primary">
              <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-muted">Belum ada data</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="text-end mt-3">
          <h6 class="fw-bold">Total Bayar: Rp 0</h6>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-2">
    <small>© 2025 Cashtify - Bootstrap Practice</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
