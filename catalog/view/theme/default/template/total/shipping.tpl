<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><a href="#collapse-shipping" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><?php echo $heading_title; ?> <i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="collapse-shipping" class="panel-collapse collapse">
    <div class="panel-body">
      <p><?php echo $text_shipping; ?></p>
      <div class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
          <div class="col-sm-10">
            <select name="country_id" id="input-country" class="form-control">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
          <div class="col-sm-10">
						<select name="parcelshopcity" id="input-parcelshopcity" class="form-control">
							</select>
          </div>
				</div>
				
				<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_handout; ?></label>
						<div class="col-sm-10">
							<select name="parcelshop" id="input-parcelshop" class="form-control">
							</select>
						</div>
					</div>

				<div class="parcel-shop-details" style="display: none;">
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_address; ?></label>
						<div class="col-sm-10">
							<p id="txtAddress"></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_addressnotes; ?></label>
						<div class="col-sm-10">
							<p id="txtAddressNotes"></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_workhours; ?></label>
						<div class="col-sm-10">
							<table class="parcel-shop-hours">
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
        <button type="button" id="button-quote" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_quote; ?></button>
      </div>
      <script type="text/javascript"><!--
$('#button-quote').on('click', function() {
	$.ajax({
		url: 'index.php?route=total/shipping/quote',
		type: 'post',
		data: 'country_id=' + $('select[name=\'country_id\']').val() + '&city=' + $('select[name=\'parcelshopcity\']').val() + '&parcelshopid=' + encodeURIComponent($('select[name=\'parcelshop\']').val()),
		dataType: 'json',
		beforeSend: function() {
			$('#button-quote').button('loading');
		},
		complete: function() {
			$('#button-quote').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['error']) {
				if (json['error']['warning']) {
					$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}

				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>');
				}

				if (json['error']['parcelshopcity']) {
					$('select[name=\'parcelshopcity\']').after('<div class="text-danger">' + json['error']['parcelshopcity'] + '</div>');
				}

				if (json['error']['parcelshop']) {
					$('select[name=\'parcelshop\']').after('<div class="text-danger">' + json['error']['parcelshop'] + '</div>');
				}
			}

			if (json['shipping_method']) {
				$('#modal-shipping').remove();

				html  = '<div id="modal-shipping" class="modal">';
				html += '  <div class="modal-dialog">';
				html += '    <div class="modal-content">';
				html += '      <div class="modal-header">';
				html += '        <h4 class="modal-title"><?php echo $text_shipping_method; ?></h4>';
				html += '      </div>';
				html += '      <div class="modal-body">';

				for (i in json['shipping_method']) {
					html += '<p><strong>' + json['shipping_method'][i]['title'] + '</strong></p>';

					if (!json['shipping_method'][i]['error']) {
						for (j in json['shipping_method'][i]['quote']) {
							html += '<div class="radio">';
							html += '  <label>';

							if (json['shipping_method'][i]['quote'][j]['code'] == '<?php echo $shipping_method; ?>') {
								html += '<input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" checked="checked" />';
							} else {
								html += '<input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" />';
							}

							html += json['shipping_method'][i]['quote'][j]['title'] + ' - ' + json['shipping_method'][i]['quote'][j]['text'] + '</label></div>';
						}
					} else {
						html += '<div class="alert alert-danger">' + json['shipping_method'][i]['error'] + '</div>';
					}
				}

				html += '      </div>';
				html += '      <div class="modal-footer">';
				html += '        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_cancel; ?></button>';

				<?php if ($shipping_method) { ?>
				html += '        <input type="button" value="<?php echo $button_shipping; ?>" id="button-shipping" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />';
				<?php } else { ?>
				html += '        <input type="button" value="<?php echo $button_shipping; ?>" id="button-shipping" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" disabled="disabled" />';
				<?php } ?>

				html += '      </div>';
				html += '    </div>';
				html += '  </div>';
				html += '</div> ';

				$('body').append(html);

				$('#modal-shipping').modal('show');

				$('input[name=\'shipping_method\']').on('change', function() {
					$('#button-shipping').prop('disabled', false);
				});
			}
		}
	});
});

$(document).delegate('#button-shipping', 'click', function() {
	$.ajax({
		url: 'index.php?route=total/shipping/shipping',
		type: 'post',
		data: 'shipping_method=' + encodeURIComponent($('input[name=\'shipping_method\']:checked').val()),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping').button('loading');
		},
		complete: function() {
			$('#button-shipping').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--

	//get list of cities
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=total/shipping/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.parcel-shop-details').hide();

			if (json['postcode_required'] == '1') {
				$('input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['cities'] && json['cities'] != '') {
				for (i = 0; i < json['cities'].length; i++) {
					html += '<option value="' + json['cities'][i] + '"';

					// if (json['cities'][i]['city'] == '<?php echo $parcelshopcity; ?>') {
					// 	html += ' selected="selected"';
					// }

					html += '>' + json['cities'][i] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'parcelshopcity\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

//get list of parcel shops by city
$('select[name=\'parcelshopcity\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=total/shipping/parcelshops&city=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'parcelshopcity\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.parcel-shop-details').hide();

			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json && json.length) {
				for (i = 0; i < json.length; i++) {
					html += '<option value="' + json[i]['id'] + '"';

					html += '>' + json[i]['address'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'parcelshop\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

//get details of a parcel shops
$('select[name=\'parcelshop\']').on('change', function() {
	if (!this.value) {
		return;
	}
	$.ajax({
		url: 'index.php?route=total/shipping/parcelShopDetails&id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'parcelshop\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {

			$('.parcel-shop-details').show();

			$('#txtAddress').text(json.address);
			$('#txtAddressNotes').text(json.addressnotes);
			$('#txtWorkhours').text('9-6');

			if (json.schedulejson)
				fillScheduleTable(JSON.parse(json.schedulejson));

			function fillScheduleTable(data) {
				if (!data) return;

				var dayNames = ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'];

				var i, $body = $('.parcel-shop-hours').find('tbody');

				$body.html('');
				for(i = 0;i < data.length; i++) {
					var dayInfo = data[i];
					var dayName = dayNames[dayInfo.WeekDay];

					$body.append(
						$('<tr>').append(
							$('<td>').text(dayName)
						).append(
							$('<td>').text(getHours(dayInfo.TimeIntervals))
						)
					);
				}

				function getHours(timeIntervals) {
					if (!timeIntervals) return '';
					var i;

					var times = [];
					for(i = 0;i < timeIntervals.length;i++) {
						var interval = timeIntervals[i];
						var fromTime = interval.From;
						var toTime = interval.To;
						var timeStr = padWithHeadingZero(fromTime.Hours) + ':' + padWithHeadingZero(fromTime.Minutes) +
							'–' + padWithHeadingZero(toTime.Hours) + ':' + padWithHeadingZero(toTime.Minutes);
						times.push(timeStr);
					}
					return times.join(', ');

					function padWithHeadingZero(unit) {
						if ((unit+'').length == 1)
							return '0' + unit;
						return unit + '';
					}
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>
    </div>
  </div>
</div>
