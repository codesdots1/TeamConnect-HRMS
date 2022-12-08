<a class="btn btn-primary" style="display: none" id="toastrBulkUpload" data-plugin="toastr" data-message="File processed successfully"
   data-container-id="toast-top-right" data-time-out="0" href="javascript:void(0)"
   role="button"></a>

<div class="modal fade" id="bulkUploadModal" aria-hidden="true" aria-labelledby="exampleModalTitle"
	 role="dialog" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal body -->
			<div class="modal-body">
				<div class="heading_demo">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title mb-3">Employee Attendance Upload</h3>
				</div>
				<div class="pull-xs-right download_btn">
					<div class="btn">
						<?php
						if(isset($bulk_upload_sample_download)) {
							foreach ($bulk_upload_sample_download as $key => $value) {
								?>
								<a href='<?= $value ?>' class='btn btn-theme text-white ctm-border-radius button-1'
								   style='color: white;' target='_blank'>Download <?= $key ?> Sample</a>
								<?php
							}
						}
						?>
					</div>
					<div class="model">
						<div class="modal-body" >
							<div class="alert alert-alt alert-danger alert-dismissible" id="uploadErrorAlertWrapper" role="alert">
                    <span id="uploadErrorAlert">
                    </span>
							</div>
							<form id="upload-file-dropzone" class="dropzone dropify-wrapper" style="height: 300px;" action="<?= $bulk_upload_url; ?>" enctype="multipart/form-data">
								<?php if(isset($bulk_upload_form_extra)) echo $bulk_upload_form_extra; ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

	var upload_dropzone = new Dropzone('#upload-file-dropzone',
		{
			maxFiles: 10,
			dictDefaultMessage: '<div class="dropify-message">' +
				'<span class="file-icon"></span> ' +
				'<p>Drag and drop a file here or click</p>' +
				'<p class="dropify-error">Ooops, something wrong appended.</p>' +
				'</div>'
			})
			.on('success', function (file, response) {
				response = JSON.parse(response);
				_results = [];

				if(response.result == 'error'){
					var message = response.error_desc.error;
					message = $(message).text();

					file.previewElement.classList.add("dz-error");
					_ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
					_results = [];
					for (_i = 0, _len = _ref.length; _i < _len; _i++) {
						node = _ref[_i];
						_results.push(node.textContent = message);
					}
				}else if (response.result == 'data_error'){
					$('#uploadErrorAlertWrapper').show();
					$('#uploadErrorAlert').html(response.errorMessage);
					$('#upload-file-dropzone').hide();
					dt_DataTable.ajax.reload();
				} else {
					$('#bulkUploadModal').modal('hide');
					dt_DataTable.ajax.reload();
					swal({
						title: "Success",
						text: "Data uploaded successfully!",
						type: "success"
					});
				}
				return _results;
			})
			.on('complete', function (data) {
			});

	$('.bulkUpload').click(function () {
		upload_dropzone.removeAllFiles();
		$('#uploadErrorAlertWrapper').hide();
		$('#uploadErrorAlert').html("");
		$('#upload-file-dropzone').show();
		$('#bulkUploadModal').modal('show');
	});


</script><?php
