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
						<script>document.write(new Date().getFullYear())</script>, made with <i
							class="fa fa-heart heart"></i> by <a href="https://www.ronstudiosoftware.com/">Ronstudio Software Jember</a>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script defer
		src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
		integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
		data-cf-beacon='{"rayId":"892dd76afc3c3fa7","b":1,"version":"2024.4.1","token":"1b7cbb72744b40c580f8633c6b62637e"}'
		crossorigin="anonymous"></script>
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
<script>
	// Facebook Pixel Code Don't Delete
	!function (f, b, e, v, n, t, s) {
		if (f.fbq) return; n = f.fbq = function () {
			n.callMethod ?
				n.callMethod.apply(n, arguments) : n.queue.push(arguments)
		}; if (!f._fbq) f._fbq = n;
		n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0;
		t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s)
	}(window,
		document, 'script', 'connect.facebook.net/en_US/fbevents.js');

	try {
		fbq('init', '111649226022273');
		fbq('track', "PageView");

	} catch (err) {
		console.log('Facebook Track Error:', err);
	}
</script>
<noscript>
	<img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=111649226022273&amp;ev=PageView&amp;noscript=1" />
</noscript>
<script type="text/javascript">

	var $table = $('#bootstrap-table');

	function operateFormatter(value, row, index) {
		return [
			'<div class="table-icons">',
			'<a rel="tooltip" title="View" class="btn btn-simple btn-info btn-icon table-action view" href="javascript:void(0)">',
			'<i class="ti-image"></i>',
			'</a>',
			'<a rel="tooltip" title="Edit" class="btn btn-simple btn-warning btn-icon table-action edit" href="javascript:void(0)">',
			'<i class="ti-pencil-alt"></i>',
			'</a>',
			'<a rel="tooltip" title="Remove" class="btn btn-simple btn-danger btn-icon table-action remove" href="javascript:void(0)">',
			'<i class="ti-close"></i>',
			'</a>',
			'</div>',
		].join('');
	}

	$().ready(function () {
		window.operateEvents = {
			'click .view': function (e, value, row, index) {
				info = JSON.stringify(row);

				swal('You click view icon, row: ', info);
				console.log(info);
			},
			'click .edit': function (e, value, row, index) {
				info = JSON.stringify(row);

				swal('You click edit icon, row: ', info);
				console.log(info);
			},
			'click .remove': function (e, value, row, index) {
				console.log(row);
				$table.bootstrapTable('remove', {
					field: 'id',
					values: [row.id]
				});
			}
		};

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
			clickToSelect: false,
			pageList: [8, 10, 25, 50, 100],

			formatShowingRows: function (pageFrom, pageTo, totalRows) {
				//do nothing here, we don't want to show the text "showing x of y from..."
			},
			formatRecordsPerPage: function (pageNumber) {
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

		//activate the tooltips after the data table is initialized
		$('[rel="tooltip"]').tooltip();

		$(window).resize(function () {
			$table.bootstrapTable('resetView');
		});
	});

</script>

<!-- Mirrored from demos.creative-tim.com/paper-dashboard-pro/bootstrap-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Jun 2024 00:09:24 GMT -->

</html>