jQuery(function () {
	jQuery.ajaxSetup({
		headers: {
			'X-CSRF-Token': jQuery('meta[name="csrf-token"]').attr('content')
		}
	});
});

jQuery(document).ready(function () {
	jQuery(document).on('click', '#sidebar-style-toggler', function () {
		var sidebar_theme = 'lite';
		if (jQuery('#page-container').hasClass('sidebar-dark')) {
			sidebar_theme = 'dark';
		}
		var url = jQuery(this).attr('data-log-url');
		jQuery.ajax({
			method: 'POST',
			url: url,
			data: { sidebar_theme: sidebar_theme }
		});
	});

	//common method for getting form html
	jQuery(document).on('click', '.get-form', function (e) {
		e.preventDefault();
		e.stopPropagation();
		//Dashmix.layout('header_loader_on');
		jQuery("#default_modal").find('.modal-content').html('');
		var url = jQuery(this).attr('href');
		jQuery.ajax({
			url: url,
			cache: false
		}).done(function (html) {
			jQuery("#default_modal").find('.modal-content').html(html);
			jQuery('#default_modal').modal('show');
		}).always(function () {
			Dashmix.layout('header_loader_off');
		});
	});

	//universal ajax submit function
	jQuery(document).on('click', '.ajax-submit', function (e) {
		e.preventDefault();
		e.stopPropagation();
		var submit_btn = jQuery(this);
		var btn_name = jQuery(this).html();
		var new_btn_name = 'Loading...'
		var form = jQuery(this).parents('form:first');
		var method = jQuery(form).attr('method');
		var url = jQuery(form).attr('action');
		var formData = new FormData(form[0]);
		if (submit_btn.attr('value') && submit_btn.attr('name')) {
			formData.append(submit_btn.attr('name'), submit_btn.attr('value'));
		}
		var target = jQuery(this).attr('data-target');
		jQuery.ajax({
			method: method,
			url: url,
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'JSON',
			beforeSend: function () {
				jQuery(submit_btn).html(new_btn_name);
				jQuery(submit_btn).attr('disabled', true);
				jQuery(form).find('.form-control').removeClass('is-invalid');
				jQuery(form).find('.invalid-feedback').remove();
				//Dashmix.layout('header_loader_on');
			},
			success: function (data) {
				if (data.response == 1) {
					swal.fire({
						title: "Done",
						text: data.msg,
						icon: "success"
					}).then(() => {
						if (data.redirect) {
							location.replace(data.redirect);
						} else if (data.reload) {
							location.reload();
						} else {
							jQuery('#default_modal').modal('hide');
							loadDataTable();
						}
					});
				} else if (data.response == 2) {
					swal.fire({
						title: "Oops...",
						text: data.msg,
						icon: "error"
					}).then(() => {
						if (data.redirect) {
							location.replace(data.redirect);
						} else {
							location.reload();
						}
					});
				} else if (data.response == 3) {
					if (data.redirect) {
						location.replace(data.redirect);
					} else {
						location.reload();
					}
				} else if (data.response == 4) {
					jQuery('#default_modal').modal('hide');
					/* Dashmix.helpers('notify', {
						from: 'bottom',
						align: 'right',
						type: data.status,
						message: data.msg
					}); */
					if (typeof target != 'undefined' && typeof data.html != 'undefined') {
						jQuery(target).html(data.html);
						refresh();
					}
				} else if (data.response == 5) {
					swal.fire({
						title: "Oops...",
						text: data.msg,
						icon: "error"
					});
				}
			},
			error: function (data) {
				
				jQuery.each(data.responseJSON.errors, function (key, index) {
					if (~key.indexOf(".")) {
						key = key.replace(/\./gi, '-');

						if (jQuery('#' + key).parent('td').length) {
							jQuery('#' + key).closest('td').append('<span class="invalid-feedback" role="alert">' + index[0] + '</span>').find('.form-control').addClass('is-invalid');
						} else if (jQuery('#' + key).length) {
							jQuery('#' + key).closest('.form-group, .input-group, .custom-file').append('<span class="invalid-feedback" role="alert">' + index[0] + '</span>').find('.form-control').addClass('is-invalid');
						} else {
							//custom multiple file input
							if (jQuery('#' + key.split('-')[0]).closest('.custom-file').find('.invalid-feedback').length === 0) {
								jQuery('#' + key.split('-')[0]).closest('.custom-file').append('<span class="invalid-feedback" role="alert">' + index[0] + '</span>').find('.form-control').addClass('is-invalid');
							}
							if (jQuery('.documentTable tr').length) {
								jQuery('.documentTable tr').eq(key.split('-')[1]).css('background-color', '#fdf1ed');
							}
						}
					} else {
						var input = jQuery(form).find('[name="' + key + '"]');
						if (input.length == 0) {
							input = jQuery(form).find('[name="' + key + '[]"]');
						}
						input.closest('.form-group, .input-group, .custom-file').append('<span class="invalid-feedback" role="alert">' + index[0] + '</span>').find('.form-control').addClass('is-invalid');
					}
				});
			},
			complete: function () {
				jQuery(submit_btn).html(btn_name);
				jQuery(submit_btn).attr('disabled', false);
				//Dashmix.layout('header_loader_off');
			}
		});
	});
	//universal approve function
	jQuery(document).on('click', '.btn-approve', function (e) {
		e.preventDefault();
		var url = jQuery(this).attr('href');
		swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Do it!',
			showLoaderOnConfirm: true
		}).then((willApprove) => {
			if (willApprove.value) {
				jQuery.ajax({
					type: 'PATCH',
					data: { _method: 'PATCH' },
					dataType: 'JSON',
					url: url,
					success: function (data) {
						if (data.response == 1) {
							swal.fire({
								title: "Updated!",
								text: data.msg,
								icon: "success"
							}).then(() => {
								if (typeof data.redirect != 'undefined') {
									location.replace(data.redirect);
								} else if (data.reload) {
									location.reload();
								} else {
									loadDataTable();
								}
							});
						} else if (data.response == 2) {
							swal.fire({ title: "Oops...", text: data.msg, icon: "error" });
						}
					},
				});
			}
		});
	});

	//universal delete function
	jQuery(document).on('click', '.btn-delete, .btn-approve', function (e) {
		e.preventDefault();
		var url = jQuery(this).attr('href');
		let desc = jQuery(this).attr('swal-desc');
		let method = jQuery(this).attr('swal-method');
		let confirmButton = jQuery(this).attr('swal-confirm');
		if (typeof desc == 'undefined') {
			desc = "You won't be able to revert this!";
		}
		if (typeof method == 'undefined') {
			method = 'DELETE';
		}
		if (typeof confirmButton == 'undefined') {
			confirmButton = 'Yes, delete it!';
		}
		swal.fire({
			title: "Are you sure?",
			text: desc,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: confirmButton,
			showLoaderOnConfirm: true
		}).then((willDelete) => {
			if (willDelete.value) {
				jQuery.ajax({
					type: method,
					data: { _method: method },
					dataType: 'JSON',
					url: url,
					success: function (data) {
						let title = data.title;
						if (typeof title == 'undefined') {
							title = "Deleted!";
						}
						if (data.response == 1) {
							swal.fire({
								title: title,
								text: data.msg,
								icon: "success"
							}).then(() => {
								if (typeof data.redirect != 'undefined') {
									location.replace(data.redirect);
								} else if (data.reload) {
									location.reload();
								} else {
									loadDataTable();
								}
							});
						} else if (data.response == 2) {
							swal.fire({ title: "Oops...", text: data.msg, icon: "error" });
						}
					},
				});
			}
		});
	});

	var current_url = window.location.href;
	jQuery('a.nav-main-link').each(function () {
		if (current_url == jQuery(this).attr('href')) {
            jQuery(this).addClass('active');
            jQuery(this).parents('.nav-main-item').addClass('open');
		}
	});

	jQuery('.border-bottom .content .nav-tabs #nav-item').on('click', function () {
		//Dashmix.layout('header_loader_on');
	});

	$.fn.modal.Constructor.prototype._enforceFocus = function () {
		var $modalElement = this.$element;
		$(document).on('focusin.modal', function (e) {
			if ($(e.target).parentsUntil('*[role="dialog"]').length === 0) {
				$modalElement.focus();
			}
		});
	};

	/* Bootstrap Tooltip */
	jQuery("body").tooltip({
		selector: '[data-toggle="tooltip"]'
	});

	// Global Search
	if (jQuery('#page-header-search-input').length) {
		jQuery('#page-header-search-input').autocomplete({
			source: function (request, response) {
				jQuery.ajax({
					dataType: "json",
					type: "POST",
					data: {
						search: request.term
					},
					url: jQuery('#page-header-search-input').parents('form:first').attr('action'),
					success: function (data) {
						jQuery('#page-header-search-input').removeClass('ui-autocomplete-loading');
						response(jQuery.map(data, function (item) {
							return {
								label: item.name,
								value: item.name,
								desc: item.desc,
								data: item
							}
						}));
					},
					error: function (data) {
						jQuery('#page-header-search-input').removeClass('ui-autocomplete-loading');
					},

				});
			},
			minLength: 2,
			select: function (event, ui) {
				if (ui.item.data.url) {
					window.location.href = ui.item.data.url;
				}
				return false;
			}
		}).autocomplete("instance")._renderItem = function (ul, item) {
			return jQuery('<li>')
				.append('<div>' + item.label + '<br><em class="font-size-sm">' + item.desc + '</em></div>')
				.appendTo(ul);
		};
	}

});