<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-hermes" data-toggle="tooltip" title="<?php echo $button_save; ?>"
          class="btn btn-primary"><i class="fa fa-save"></i></button>
        
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
          class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-hermes"
          class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="hermes_updateprices">
              <span data-toggle="tooltip"
                title="<?php echo $help_updateprices; ?>"><?php echo $entry_updateprices; ?></span></label>
            <div class="col-sm-10">
              <input type="checkbox" id="hermes_updateprices" name="hermes_updateprices" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="hermes_updateparcelshops">
              <span data-toggle="tooltip"
                title="<?php echo $help_updateparcelshops; ?>"><?php echo $entry_updateparcelshops; ?></span></label>
            <div class="col-sm-10">
              <input type="checkbox" id="hermes_updateparcelshops" name="hermes_updateparcelshops" />
            </div>
          </div>
          <div class="form-group">
              <label class="col-sm-2 control-label" for="hermes_updateparcelshops">
                <span data-toggle="tooltip"
                  title="<?php echo $help_updateparcelshops; ?>">Test/Prod toggler</span></label>
              <div class="col-sm-10">   
                  <label class="radio-inline">
                      <?php if ($hermes_test) { ?>
                      <input type="radio" name="hermes_test" value="1" checked="checked" />
                      test
                      <?php } else { ?>
                      <input type="radio" name="hermes_test" value="1" />
                      test
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$hermes_test) { ?>
                      <input type="radio" name="hermes_test" value="0" checked="checked" />
                      prod
                      <?php } else { ?>
                      <input type="radio" name="hermes_test" value="0" />
                      prod
                      <?php } ?>
                    </label>         
              </div>
            </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meter">Business unit code</label>
                <div class="col-sm-10">
                  <?php if($hermes_test) {?>

                    <input type="text" name="hermes_bu_code" value="1000" 
                  id="txtBusinessUnitCode" class="form-control" readonly/>

                  <?php } else { ?>
                    <input type="text" name="hermes_bu_code" value="3279" 
                  id="txtBusinessUnitCode" class="form-control" readonly/>
                  <?php } ?>
                  
                </div>
              </div>

              <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-meter">Pickup location code</label>
                  <div class="col-sm-10">
                      <?php if($hermes_test) {?>

                        <input type="text" name="hermes_pickup_location" value="437" 
                      id="txtPickupLocationCode" class="form-control" readonly/>
    
                      <?php } else { ?>
                        <input type="text" name="hermes_pickup_location" value="1969" 
                      id="txtPickupLocationCode" class="form-control" readonly/>
                      <?php } ?>
                  </div>
                </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="hermes_status" id="input-status" class="form-control">
                <?php if ($hermes_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="hermes_sort_order" value="<?php echo $hermes_sort_order; ?>"
                placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>