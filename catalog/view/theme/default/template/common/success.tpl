<?php echo $header; ?>
<div class="container">
  <!-- <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul> -->
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <!-- <?php echo $text_message; ?> -->

      <div class="order-info-msg">
          <p>
              Спасибо за покупку огнетушителя ВИШНЯ!
          </p>
          
          <p> Ваш заказ № <?php echo $order_id; ?> успешно оформлен.</p>

          <p>В ближайшее время вам придет письмо на указанный e-mail со всей информацией по заказу.</p>
          
          <p>
              Если у Вас возникли какие-либо вопросы, свяжитесь с нами по следующим телефонам с 9 до 18 часов по московскому времени:
              8 800 500-67-46 и +7 (812) 407-38-41.
          </p>
          
          <p>
              Или напишите нам на почту <a href="mailto:zakaz@legionfirst.ru">zakaz@legionfirst.ru</a>
          </p>
      </div>

      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>