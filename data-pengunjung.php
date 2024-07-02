<?php
session_start();

// Periksa apakah ada sesi LoginFirst yang sudah diatur dan valid
if (!isset($_SESSION['LoginFirst'])) {
	header("Location: index.php");
	exit();
}

?>
<?php
include 'layouts/header.php';
?>

<body>

	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

	<div class="wrapper">
		<div class="sidebar" data-background-color="brown" data-active-color="danger">
			<div class="logo">
				<a href="#" class="simple-text logo-mini">
					P
				</a>
				<a href="#" class="simple-text logo-normal">
					Perpustakaan
				</a>
			</div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<ul class="nav">
						<li>
							<a href="home.php">
								<i class="ti-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="active">
							<a href="data-pengunjung.php">
								<i class="ti-server"></i>
								<p>Data Pengunjung</p>
							</a>
						</li>

						<li>
							<a href="prediksi.php">
								<i class="ti-stats-up"></i>
								<p>Forum Prediksi</p>
							</a>
						</li>
						<li>
							<a href="hasil.php">
								<i class="ti-clipboard"></i>
								<p>Hasil</p>
							</a>
						</li>

						<li>
							<a href="Logout_Behind.php">
								<i class="ti-arrow-left"></i>
								<p>Logout</p>
							</a>
						</li>
					</ul>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-minimize">
						<button id="minimizeSidebar" class="btn btn-fill btn-icon"><i class="ti-more-alt"></i></button>
					</div>
				</div>
			</nav>
			<script>
				function filterByYear() {
					var year = document.getElementById("yearFilter").value;
					window.location.href = "?year=" + year;
				}
			</script>
			<div class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="yearFilter">Filter by Year:</label>
								<select id="yearFilter" class="form-control" onchange="filterByYear()">
									<option value="all" <?php echo (!isset($_GET['year']) || $_GET['year'] == 'all') ? 'selected' : ''; ?>>All</option>
									<option value="2022" <?php echo (isset($_GET['year']) && $_GET['year'] == '2022') ? 'selected' : ''; ?>>2022</option>
									<option value="2023" <?php echo (isset($_GET['year']) && $_GET['year'] == '2023') ? 'selected' : ''; ?>>2023</option>
									<option value="2024" <?php echo (isset($_GET['year']) && $_GET['year'] == '2024') ? 'selected' : ''; ?>>2024</option>
								</select>
							</div>
							<div class="card">
								<div class="card-content">

									<?php
									// Koneksi ke database (misalnya menggunakan DBConnection::getdbconnection() atau koneksi lainnya)
									include_once 'MyFrameworks/DBConnection.php';
									$dbConnection = new DBConnection();
									$connection = $dbConnection->getDBConnection();

									// Query untuk mengambil data dari tabel tbl_kunjungan
									$query = "SELECT * FROM tbl_kunjungan";
									// Jika ada filter tahun
									if (isset($_GET['year']) && $_GET['year'] != 'all') {
										$year = intval($_GET['year']);
										$query .= " WHERE YEAR(tanggal) = $year";
									}
									$query .= " ORDER BY id DESC LIMIT 350";
									$result = mysqli_query($connection, $query);
									
									// Mulai tabel HTML
									echo '<table id="bootstrap-table" class="table">';
									echo '<thead>';
									echo '<tr>';
									echo '<th data-field="state" data-checkbox="true"></th>';
									echo '<th data-field="nomer_kunjungan" data-sortable="true">No. Kunjungan</th>';
									echo '<th data-field="nama" data-sortable="true">Nama</th>';
									echo '<th data-field="jk" data-sortable="true">Jenis Kelamin</th>';
									echo '<th data-field="pekerjaan" data-sortable="true">Pekerjaan</th>';
									echo '<th data-field="pendidikan" data-sortable="true">Pendidikan</th>';
									echo '<th data-field="informasi_dicari">Tanggal Kunjungan</th>';
									echo '<th data-field="actions" class="td-actions text-right"
												>Actions</th>';
									echo '</tr>';
									echo '</thead>';
									echo '<tbody>';

									// Loop untuk menampilkan data
									while ($row = mysqli_fetch_assoc($result)) {
										echo '<tr>';
										echo '<td></td>'; // Kolom checkbox, bisa diisi dengan fitur checkbox jika diperlukan
										echo '<td>' . htmlspecialchars($row['nomer_kunjungan']) . '</td>'; // Nomer Kunjungan
										echo '<td>' . htmlspecialchars($row['nama']) . '</td>'; // Nama
										echo '<td>' . htmlspecialchars($row['jk']) . '</td>'; // Jenis Kelamin
										echo '<td>' . htmlspecialchars($row['pekerjaan']) . '</td>'; // Pekerjaan
										echo '<td>' . htmlspecialchars($row['pendidikan']) . '</td>'; // Pendidikan
										echo '<td>' . htmlspecialchars($row['tanggal'] . ' ' . date('H:i:s', strtotime($row['waktu']))) . '</td>';
										echo '<td>';
										echo '<div class="table-icons">';

										// Tombol View
										echo '<a rel="tooltip" title="View" class="btn btn-simple btn-info btn-icon table-action view" href="javascript:void(0)" data-toggle="modal" data-target="#viewModal_' . $row['id'] . '">';
										echo '<i class="ti-image"></i>';
										echo '</a>';

										// Tombol Edit
										echo '<a rel="tooltip" title="Edit" class="btn btn-simple btn-warning btn-icon table-action edit" href="javascript:void(0)" data-toggle="modal" data-target="#editModal_' . $row['id'] . '">';
										echo '<i class="ti-pencil-alt"></i>';
										echo '</a>';

										// Tombol Hapus
										echo '<a href="data-pengunjung.php?id=' . $row['id'] . '" rel="tooltip" title="Hapus" class="btn btn-simple btn-danger btn-icon" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">';
										echo '<i class="ti-close"></i>';
										echo '</a>';

										echo '</div>';
										echo '</td>';
										echo '</tr>';

										// Modal Edit untuk setiap baris data
										echo '<div class="modal fade" id="editModal_' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel_' . $row['id'] . '" aria-hidden="true" data-backdrop="static">';
										echo '<div class="modal-dialog modal-dialog-scrollable" role="document">';
										echo '<div class="modal-content">';
										echo '<div class="modal-header">';
										echo '<h5 class="modal-title" id="editModalLabel_' . $row['id'] . '">Edit Data Pengunjung</h5>';
										echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
										echo '<span aria-hidden="true">&times;</span>';
										echo '</button>';
										echo '</div>';
										echo '<div class="modal-body">';
										echo '<!-- Form Edit Data -->';
										echo '<form id="editForm' . $row['id'] . '">';
										echo '<input type="hidden" id="edit_id_' . $row['id'] . '" name="edit_id" value="' . $row['id'] . '">';
										echo '<div class="form-group">';
										echo '<label for="edit_nomer_kunjungan_' . $row['id'] . '">Nomer Kunjungan</label>';
										echo '<input type="text" class="form-control" id="edit_nomer_kunjungan_' . $row['id'] . '" name="edit_nomer_kunjungan" value="' . htmlspecialchars($row['nomer_kunjungan']) . '" required>';
										echo '</div>';
										echo '<div class="form-group">';
										echo '<label for="edit_nama_' . $row['id'] . '">Nama</label>';
										echo '<input type="text" class="form-control" id="edit_nama_' . $row['id'] . '" name="edit_nama" value="' . htmlspecialchars($row['nama']) . '" required>';
										echo '</div>';
										// Jenis Kelamin (select option)
										echo '<div class="form-group">';
										echo '<label for="edit_jk_' . $row['id'] . '">Jenis Kelamin</label>';
										echo '<select class="form-control" id="edit_jk_' . $row['id'] . '" name="edit_jk" required>';

										// Opsi jenis kelamin
										$jenis_kelamin_options = array(
											'Laki-laki' => 'Laki-laki',
											'Perempuan' => 'Perempuan'
										);

										// Loop untuk menampilkan opsi jenis kelamin
										foreach ($jenis_kelamin_options as $key => $value) {
											$selected = ($row['jk'] == $key) ? 'selected' : ''; // Memeriksa apakah jenis kelamin saat ini cocok dengan opsi saat ini
											echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
										}

										echo '</select>';
										echo '</div>';

										echo '<div class="form-group">';
										echo '<label for="edit_pekerjaan_' . $row['id'] . '">Pekerjaan</label>';
										echo '<input type="text" class="form-control" id="edit_pekerjaan_' . $row['id'] . '" name="edit_pekerjaan" value="' . htmlspecialchars($row['pekerjaan']) . '" required>';
										echo '</div>';
										echo '<div class="form-group">';
										echo '<label for="edit_pendidikan_' . $row['id'] . '">Pendidikan</label>';
										echo '<input type="text" class="form-control" id="edit_pendidikan_' . $row['id'] . '" name="edit_pendidikan" value="' . htmlspecialchars($row['pendidikan']) . '" required>';
										echo '</div>';
										echo '<div class="form-group">';
										echo '<label for="edit_lokasi_ruangan_' . $row['id'] . '">Lokasi Ruangan</label>';
										echo '<input type="text" class="form-control" id="edit_lokasi_ruangan_' . $row['id'] . '" name="edit_lokasi_ruangan" value="' . htmlspecialchars($row['lokasi_ruangan']) . '" required>';
										echo '</div>';
										echo '<div class="form-group">';
										echo '<label for="edit_tanggal_' . $row['id'] . '">Tanggal Kunjungan</label>';
										echo '<input type="datetime-local" class="form-control" id="edit_tanggal_' . $row['id'] . '" name="edit_tanggal" value="' . htmlspecialchars($row['tanggal'] . ' ' . $row['waktu']) . '" required>';
										echo '</div>';
										echo '</form>';
										echo '</div>';
										echo '<div class="modal-footer">';
										echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
										echo '<button type="button" class="btn btn-primary" id="editButton_' . $row['id'] . '">Update changes</button>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
										echo '</div>';

										// Modal View untuk setiap baris data
										echo '<div class="modal fade" id="viewModal_' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel_' . $row['id'] . '" aria-hidden="true" data-backdrop="static">';
										echo '<div class="modal-dialog modal-dialog-scrollable" role="document">';
										echo '<div class="modal-content">';
										echo '<div class="modal-header">';
										echo '<h5 class="modal-title" id="viewModalLabel_' . $row['id'] . '">Detail Data Pengunjung</h5>';
										echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
										echo '<span aria-hidden="true">&times;</span>';
										echo '</button>';
										echo '</div>';
										echo '<div class="modal-body">';
										echo '<div class="row">';
										echo '<div class="col-md-6">';
										echo '<div><strong>Nomer Kunjungan:</strong> ' . htmlspecialchars($row['nomer_kunjungan']) . '</div>';
										echo '<div><strong>Nama:</strong> ' . htmlspecialchars($row['nama']) . '</div>';
										echo '<div><strong>Jenis Kelamin:</strong> ' . htmlspecialchars($row['jk']) . '</div>';
										echo '<div><strong>Pekerjaan:</strong> ' . htmlspecialchars($row['pekerjaan']) . '</div>';
										echo '<div><strong>Pendidikan:</strong> ' . htmlspecialchars($row['pendidikan']) . '</div>';
										echo '</div>';
										echo '<div class="col-md-6">';
										echo '<div><strong>Lokasi Perpustakaan:</strong> ' . htmlspecialchars($row['lokasi_perpustakaan']) . '</div>';
										echo '<div><strong>Lokasi Ruangan:</strong> ' . htmlspecialchars($row['lokasi_ruangan']) . '</div>';
										echo '<div><strong>Tanggal Kunjungan:</strong> ' . htmlspecialchars($row['tanggal'] . ' ' . $row['waktu']) . '</div>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
										echo '<div class="modal-footer">';
										echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
									}

									// Akhiri tabel HTML
									echo '</tbody>';
									echo '</table>';

									// Tutup koneksi database jika sudah tidak digunakan lagi
									mysqli_close($connection);
									?>
									<!-- Modal Tambah Data -->
									<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true" data-backdrop="static">
										<div class="modal-dialog modal-dialog-scrollable" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="tambahModalLabel">Tambah Data Pengunjung</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<!-- Form Tambah Data -->
													<form id="tambahForm">
														<div class="form-group">
															<label for="nomer_kunjungan">No. Kunjungan</label>
															<input type="text" class="form-control" id="nomer_kunjungan" name="nomer_kunjungan" required>
														</div>
														<div class="form-group">
															<label for="nama">Nama</label>
															<input type="text" class="form-control" id="nama" name="nama" required>
														</div>
														<div class="form-group">
															<label for="tambah_jk">Jenis Kelamin</label>
															<select class="form-control" id="tambah_jk" name="tambah_jk" required>
																<?php
																// Nilai enum yang diketahui untuk kolom jk
																$jenis_kelamin_options = ['Laki-laki', 'Perempuan'];

																// Loop untuk menampilkan pilihan jenis kelamin dalam select
																foreach ($jenis_kelamin_options as $jk) {
																	echo '<option value="' . $jk . '">' . $jk . '</option>';
																}
																?>
															</select>

														</div>

														<div class="form-group">
															<label for="tambah_pekerjaan">Pekerjaan</label>
															<input type="text" class="form-control" id="tambah_pekerjaan" name="tambah_pekerjaan" required>
														</div>
														<div class="form-group">
															<label for="tambah_pendidikan">Pendidikan</label>
															<input type="text" class="form-control" id="tambah_pendidikan" name="tambah_pendidikan" required>
														</div>
														<div class="form-group">
															<label for="tambah_lokasi_ruangan">Lokasi Ruangan</label>
															<input type="text" class="form-control" id="tambah_lokasi_ruangan" name="tambah_lokasi_ruangan" required>
														</div>
														<div class="form-group">
															<label for="tanggal_waktu">Tanggal Kunjungan</label>
															<input type="datetime-local" class="form-control" id="tanggal_waktu" name="tanggal_waktu" required>
														</div>

														<div class="modal-footer">
															<button type="reset" class="btn btn-secondary">Batal</button>
															<button type="button" class="btn btn-primary" id="tambahButton">Tambah Data</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul>
							<li>
								<a href="#">
									Library Aziza Azka.
								</a>
							</li>
							<li>
								<a href="https://blog.creative-tim.com/">
									Blog
								</a>
							</li>
							<li>
								<a href="https://www.creative-tim.com/license">
									Licenses
								</a>
							</li>
						</ul>
					</nav>
					<div class="copyright pull-right">
						&copy;
						<script>
							document.write(new Date().getFullYear())
						</script>, made with <i class="fa fa-heart heart"></i> by <a href="https://www.ronstudiosoftware.com/">Ronstudio Software Jember</a>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"892dd76afc3c3fa7","b":1,"version":"2024.4.1","token":"1b7cbb72744b40c580f8633c6b62637e"}' crossorigin="anonymous"></script>
</body>

<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/js/perfect-scrollbar.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

<script src="assets/js/jquery.validate.min.js"></script>

<script src="assets/js/es6-promise-auto.min.js"></script>

<script src="assets/js/moment.min.js"></script>

<script src="assets/js/bootstrap-datetimepicker.js"></script>

<script src="assets/js/bootstrap-selectpicker.js"></script>

<script src="assets/js/bootstrap-switch-tags.js"></script>

<script src="assets/js/jquery.easypiechart.min.js"></script>

<script src="assets/js/chartist.min.js"></script>

<script src="assets/js/bootstrap-notify.js"></script>

<script src="assets/js/sweetalert2.js"></script>

<script src="assets/js/jquery-jvectormap.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFPQibxeDaLIUHsC6_KqDdFaUdhrbhZ3M"></script>

<script src="assets/js/jquery.bootstrap.wizard.min.js"></script>

<script src="assets/js/bootstrap-table.js"></script>

<script src="assets/js/jquery.datatables.js"></script>

<script src="assets/js/fullcalendar.min.js"></script>

<script src="assets/js/paper-dashboard.js"></script>

<script src="assets/js/jquery.sharrre.js"></script>

<script src="assets/js/demo.js"></script>
<div class="fixed-plugin">
	<div class="dropdown">
		<a href="" data-toggle="modal" data-target="#tambahModal">
			<i class="fa fa-plus fa-3x"> </i>
		</a>
	</div>
</div>

<script>
	var $table = $('#bootstrap-table');

	$(function() {
		$table.bootstrapTable({
			toolbar: ".toolbar",
			clickToSelect: true,
			showRefresh: true,
			search: true,
			showToggle: true,
			showColumns: true,
			pagination: true,
			searchAlign: 'left',
			pageSize: 8,
			pageList: [8, 10, 25, 50, 100],
			formatShowingRows: function(pageFrom, pageTo, totalRows) {
				// Do nothing here, we don't want to show the text "showing x of y from..."
			},
			formatRecordsPerPage: function(pageNumber) {
				return pageNumber + " rows visible";
			},
			icons: {
				refresh: 'fa fa-refresh',
				toggle: 'fa fa-th-list',
				columns: 'fa fa-columns',
				detailOpen: 'fa fa-plus-circle',
				detailClose: 'ti-close'
			}
		});

		// Activate tooltips after the data table is initialized
		$('[rel="tooltip"]').tooltip();

		// Reset table view when window is resized
		$(window).resize(function() {
			$table.bootstrapTable('resetView');
		});
	});
</script>


<script type="text/javascript">
	$(function() {
		$('#tambahButton').click(function() {
			// Validasi form sebelum submit
			var form = $('#tambahForm')[0];
			if (!form.checkValidity()) {
				form.reportValidity();
				return;
			}

			var formData = $('#tambahForm').serializeArray();
			var datetime = moment($('#tanggal_waktu').val(), 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm:ss').split(' ');

			formData.push({
				name: 'tanggal',
				value: datetime[0]
			});
			formData.push({
				name: 'waktu',
				value: datetime[1]
			});

			$.ajax({
				type: 'POST',
				url: 'data-pengunjung.php',
				data: $.param(formData),
				success: function(response) {
					then(function() {
						window.location = "data-pengunjung.php";
					});
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
					Swal.fire({
						title: "Error!",
						text: "Terjadi kesalahan. Data gagal ditambahkan.",
						icon: "error",
						confirmButtonText: "OK"
					});
				}
			});
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		// Event ketika modal ditampilkan
		$('#tambahModal').on('shown.bs.modal', function() {
			// Simpan status modal (buka/tutup) ke localStorage
			localStorage.setItem('modalShown', 'true');
		});

		// Event ketika modal ditutup
		$('#tambahModal').on('hidden.bs.modal', function() {
			// Hapus status modal dari localStorage saat modal ditutup
			localStorage.removeItem('modalShown');
		});

		// Cek status modal saat halaman dimuat
		var modalShown = localStorage.getItem('modalShown');
		if (modalShown === 'true') {
			// Jika modal harus terbuka, tampilkan modal
			$('#tambahModal').modal('show');
		}

		// Event pada tombol "Tambah Data" atau form submit
		$('#tambahButton').click(function() {
			// Validasi form sebelum submit
			var form = $('#tambahForm')[0];
			if (!form.checkValidity()) {
				form.reportValidity();
				return;
			}
			// Lakukan pengiriman data atau operasi lainnya

			// Tampilkan SweetAlert
			Swal.fire({
				title: "Berhasil!",
				text: "Data Anda telah berhasil ditambahkan.",
				icon: "success",
				showConfirmButton: false,
				timer: 1500
			}).then(function() {
				// Setelah menampilkan SweetAlert, reload halaman setelah beberapa saat
				setTimeout(function() {
					location.reload();
				}, 1000); // Ganti nilai 1000 (ms) sesuai kebutuhan
			});
		});
	});
</script>


<?php
include_once 'MyFrameworks/DBConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Ambil data dari form
	$nomer_kunjungan = $_POST['nomer_kunjungan'];
	$nama = $_POST['nama'];
	$jk = $_POST['tambah_jk'];
	$pekerjaan = $_POST['tambah_pekerjaan'];
	$pendidikan = $_POST['tambah_pendidikan'];
	$lokasi_ruangan = $_POST['tambah_lokasi_ruangan'];
	$tanggal = $_POST['tanggal'];
	$waktu = $_POST['waktu'];

	// Koneksi ke database
	$dbConnection = new DBConnection();
	$connection = $dbConnection->getDBConnection();

	// Query untuk memasukkan data
	$query = "INSERT INTO tbl_kunjungan (nomer_kunjungan, nama, jk, pekerjaan, pendidikan, lokasi_ruangan, tanggal, waktu) 
              VALUES ('$nomer_kunjungan', '$nama', '$jk', '$pekerjaan', '$pendidikan', '$lokasi_ruangan', '$tanggal', '$waktu')";

	// Eksekusi query
	if (mysqli_query($connection, $query)) {
		echo "Data berhasil ditambahkan!";
	} else {
		echo "Error: " . $query . "<br>" . mysqli_error($connection);
	}

	// Tutup koneksi
	mysqli_close($connection);
} else {
	echo "Metode request tidak valid.";
}
?>

<script type="text/javascript">
	$(function() {
		// Event saat tombol edit di-klik
		$('[id^="editButton_"]').click(function() {
			var id = this.id.split('_')[1]; // Mendapatkan id dari tombol edit

			// Validasi form sebelum submit
			var form = $('#editForm' + id)[0];
			if (!form.checkValidity()) {
				form.reportValidity();
				return;
			}

			// Mengumpulkan data dari form
			var formData = $('#editForm' + id).serializeArray();
			var datetime = moment($('#edit_tanggal_' + id).val(), 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm:ss').split(' ');

			formData.push({
				name: 'tanggal',
				value: datetime[0]
			});
			formData.push({
				name: 'waktu',
				value: datetime[1]
			});
			// Ajax untuk mengirim data edit
			$.ajax({
				type: 'POST',
				url: 'data-pengunjung.php', // Ubah url sesuai dengan file untuk edit data
				data: $.param(formData),
				success: function(response) {
					Swal.fire({
						title: "Berhasil!",
						text: "Data berhasil diubah.",
						icon: "success",
						showConfirmButton: false,
						timer: 1500
					}).then(function() {
						window.location = "data-pengunjung.php";
					});
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
					Swal.fire({
						title: "Error!",
						text: "Terjadi kesalahan. Data gagal diubah.",
						icon: "error",
						confirmButtonText: "OK"
					});
				}
			});
		});
	});
</script>

<?php
include_once 'MyFrameworks/DBConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Ambil data dari form
	$id = $_POST['edit_id'];
	$nomer_kunjungan = $_POST['edit_nomer_kunjungan'];
	$nama = $_POST['edit_nama'];
	$jk = $_POST['edit_jk'];
	$pekerjaan = $_POST['edit_pekerjaan'];
	$pendidikan = $_POST['edit_pendidikan'];
	$lokasi_ruangan = $_POST['edit_lokasi_ruangan'];
	$tanggal = $_POST['tanggal']; // Ambil tanggal dan waktu dari form
	$waktu = $_POST['waktu']; // Ambil tanggal dan waktu dari form

	// Koneksi ke database
	$dbConnection = new DBConnection();
	$connection = $dbConnection->getDBConnection();

	// Query untuk update data
	$query = "UPDATE tbl_kunjungan SET 
                nomer_kunjungan = '$nomer_kunjungan',
                nama = '$nama',
                jk = '$jk',
                pekerjaan = '$pekerjaan',
                pendidikan = '$pendidikan',
                lokasi_ruangan = '$lokasi_ruangan',
                tanggal = '$tanggal',
                waktu = '$waktu'
              WHERE id = '$id'";

	// Eksekusi query
	if (mysqli_query($connection, $query)) {
		echo "Data berhasil diubah!";
	} else {
		echo "Error: " . $query . "<br>" . mysqli_error($connection);
	}

	// Tutup koneksi
	mysqli_close($connection);
}
?>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php
// Misalnya, koneksi ke database menggunakan file koneksi yang sudah ada
include_once 'MyFrameworks/DBConnection.php';
$dbConnection = new DBConnection();
$connection = $dbConnection->getDBConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
	// Escape input untuk menghindari SQL injection
	$id = mysqli_real_escape_string($connection, $_GET['id']);

	// Query DELETE untuk menghapus data pengunjung berdasarkan id
	$query = "DELETE FROM tbl_kunjungan WHERE id = '$id'";

	// Eksekusi query
	if (mysqli_query($connection, $query)) {
		// Jika penghapusan berhasil, kirimkan pesan sukses ke halaman sebelumnya
		echo '<script>
                Swal.fire({
                    title: "Terhapus!",
                    text: "Data Anda telah berhasil dihapus.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "data-pengunjung.php";
                });
              </script>';
	} else {
		// Jika terjadi kesalahan, tampilkan pesan error
		echo '<script>
                Swal.fire({
                    title: "Gagal!",
                    text: "Gagal menghapus data.",
                    icon: "error",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "data-pengunjung.php";
                });
              </script>';
	}
}

// Tutup koneksi database jika sudah tidak digunakan lagi
mysqli_close($connection);
?>




<!-- Mirrored from demos.creative-tim.com/paper-dashboard-pro/bootstrap-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Jun 2024 00:09:24 GMT -->

</html>