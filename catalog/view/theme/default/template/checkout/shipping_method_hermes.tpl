<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>

<div class="shipping_method_wrapper">

  <div class="form-horizontal">
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
      <div class="col-sm-10">
        <select name="country_id" id="input-country" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($countries as $country) { ?>
          <?php if ($country['country_id'] == $country_id) { ?>
          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?>
          </option>
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
    <p>
      <span id="calc-quote-title"></span>&nbsp;-&nbsp;<span id="calc-quote-amount"></span>
      <input type="hidden" id="parcelshopid" name="parcelshopid" value="12" />
      <input type="hidden" id="parcelshopcityid" name="parcelshopcityid" />
      <input type="hidden" id="shipping_method" name="shipping_method" />
    </p>
    <p><strong><?php echo $text_comments; ?></strong></p>
    <p>
      <textarea name="comment" rows="8" class="form-control"><?php echo $comment; ?></textarea>
    </p>
    <div class="buttons">
      <div class="pull-right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method"
          data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
      </div>
    </div>
  </div>
  <script type="text/javascript">
    //   //get list of cities
    $('select[name=\'country_id\']').on('change', function () {
      $.ajax({
        url: 'index.php?route=total/shipping/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function () {
          $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
        },
        complete: function () {
          $('.fa-spin').remove();
        },
        success: function (json) {
          $('.parcel-shop-details').hide();

          if (json['postcode_required'] == '1') {
            $('input[name=\'postcode\']').parent().parent().addClass('required');
          } else {
            $('input[name=\'postcode\']').parent().parent().removeClass('required');
          }

          html = '<option value=""><?php echo $text_select; ?></option>';

          var selectedCity = '<?php echo $parcelshopcityid; ?>';
          if (json['cities'] && json['cities'] != '') {
            
            for (i = 0; i < json['cities'].length; i++) {
              var cityName = json['cities'][i];
              html += '<option value="' + cityName + '"';

              if (cityName == selectedCity) {
                html += ' selected="selected"';
              }

              html += '>' + cityName + '</option>';
            }
          } else {
            html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
          }

          $('select[name=\'parcelshopcity\']').html(html);

          if (selectedCity) {
            $('select[name=\'parcelshopcity\']').trigger('change');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    });

    // //get list of parcel shops by city
    $('select[name=\'parcelshopcity\']').on('change', function () {
      $('#parcelshopcityid').val(this.value);

      $.ajax({
        url: 'index.php?route=total/shipping/parcelshops&city=' + this.value,
        dataType: 'json',
        beforeSend: function () {
          $('select[name=\'parcelshopcity\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
        },
        complete: function () {
          $('.fa-spin').remove();
        },
        success: function (json) {
          $('.parcel-shop-details').hide();

          html = '<option value=""><?php echo $text_select; ?></option>';
          var selectedParcelShop = '<?php echo $parcelshopid; ?>';

          if (json && json.length) {
            
            for (i = 0; i < json.length; i++) {
              var shopId = json[i]['id'];
              html += '<option value="' + shopId + '"';
              if (shopId == selectedParcelShop) {
                html += ' selected="selected"';
              }

              html += '>' + json[i]['address'] + '</option>';
            }
          } else {
            html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
          }

          $('select[name=\'parcelshop\']').html(html);
          if (selectedParcelShop)
            $('select[name=\'parcelshop\']').trigger('change');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    });


    // //get details of a parcel shops
    $('select[name=\'parcelshop\']').on('change', function () {
      if (!this.value) {
        return;
      }

      $('#parcelshopid').val(this.value);
      $('#shipping_method').val('hermes.hermes');

      $.ajax({
        url: 'index.php?route=total/shipping/quote',
        type: 'post',
        data: 'country_id=' + $('select[name=\'country_id\']').val() + '&city=' + $(
          'select[name=\'parcelshopcity\']').val() + '&parcelshopid=' + encodeURIComponent($(
          'select[name=\'parcelshop\']').val()),
        dataType: 'json',
        beforeSend: function () {
          $('#button-quote').button('loading');
        },
        complete: function () {
          $('#button-quote').button('reset');
        },
        success: function (json) {
          var hermesQuote = json.shipping_method;
          if (!json.shipping_method) {
            return;
          }
          if (!json.shipping_method.hermes) {
            return;
          }

          var quote = json.shipping_method.hermes.quote.hermes;
          $('#calc-quote-title').text(quote.title);
          $('#calc-quote-amount').text(quote.text);
        }
      });
      $.ajax({
        url: 'index.php?route=total/shipping/parcelShopDetails&id=' + this.value,
        dataType: 'json',
        beforeSend: function () {
          $('select[name=\'parcelshop\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
        },
        complete: function () {
          $('.fa-spin').remove();
        },
        success: function (json) {

          $('.parcel-shop-details').show();

          $('#txtAddress').text(json.address);
          $('#txtAddressNotes').text(json.addressnotes);
          $('#txtWorkhours').text('9-6');

          if (json.schedulejson)
            fillScheduleTable(JSON.parse(json.schedulejson));

          function fillScheduleTable(data) {
            if (!data) return;

            var dayNames = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница',
            'Суббота'];

            var i, $body = $('.parcel-shop-hours').find('tbody');

            $body.html('');
            for (i = 0; i < data.length; i++) {
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
              for (i = 0; i < timeIntervals.length; i++) {
                var interval = timeIntervals[i];
                var fromTime = interval.From;
                var toTime = interval.To;
                var timeStr = padWithHeadingZero(fromTime.Hours) + ':' + padWithHeadingZero(fromTime
                  .Minutes) +
                  '–' + padWithHeadingZero(toTime.Hours) + ':' + padWithHeadingZero(toTime.Minutes);
                times.push(timeStr);
              }
              return times.join(', ');

              function padWithHeadingZero(unit) {
                if ((unit + '').length == 1)
                  return '0' + unit;
                return unit + '';
              }
            }
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    });

    $('select[name=\'country_id\']').trigger('change');
  </script>
</div>