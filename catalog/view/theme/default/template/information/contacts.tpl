<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?> article-wrapper">
      <?php echo $content_top; ?>

      <div class="row">
        <div class="col-sm-12">
            <h1><?php echo $heading_title; ?></h1>
        </div>
      </div> <!--<div class="row">-->

      <div class="row">
        <div class="col-sm-6">
            <p>
                Если у Вас остались какие-либо вопросы, напишите нам.
              </p>
              <p>
                <strong>Центральный офис:</strong>
                <span>192012, Санкт-Петербург, проспект Обуховской Обороны, 120Л</span>
                <br>
                <strong>Бесплатный звонок по России:</strong>
                <span>8-800-500-67-46</span>
                <br>
                <strong>Номер телефона:</strong>
                <span>+7 (812) 407-38-41</span>
                <br>
                <strong>E-mail:</strong>
                <a href="mailto:info@legionfirst.ru">info@legionfirst.ru</a>
              </p>
        </div>

        <div class="col-sm-6">
          <div style="padding-bottom:56.25%; position:relative; display:block; width: 100%">
              <iframe src="https://yandex.com/map-widget/v1/-/CCGRnR0s" 
              width="100%" height="100%" 
              style="position:absolute; top:0; left: 0"
              frameborder="1" allowfullscreen="true"></iframe>
          </div>
        </div>

      </div> <!--<div class="row">-->

      <div class="row margin-top-sm">
        <div class="col-sm-12">
            <div class="panel panel-default" >          
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a href="#collapse-checkout-option1" data-toggle="collapse" data-parent="#accordion"
                      class="accordion-toggle collapsed" aria-expanded="false">Реквизиты для юридических лиц
                      <i class="fa fa-caret-down"></i>
                    </a>
                  </h4>
                </div>
                <div class="panel-collapse collapse" id="collapse-checkout-option1" aria-expanded="false"
                  style="height: 0px;">
                  <div class="panel-body">
                    <table>
    
                      <thead>
                        <tr>
                          <th width="40%"></th>
    
                          <th width="60%"></th>
    
                        </tr>
                      </thead>
    
                      <tbody>
    
                        <tr>
    
                          <td>Полное наименование организации</td>
    
                          <td>Общество с ограниченной ответственностью «Первый Легион»</td>
    
                        </tr>
    
                        <tr>
    
                          <td>Сокращенное название организации</td>
    
                          <td>ООО «Первый Легион»</td>
    
                        </tr>
    
                        <tr>
    
                          <td>Юридический адрес</td>
    
                          <td>191014, г. Санкт-Петербург, ул. Радищева, 26</td>
    
                        </tr>
    
                        <tr>
    
                          <td>Фактический адрес</td>
    
                          <td>192012, г. Санкт – Петербург, пр. Обуховской Обороны, д. 120 лит. Л, <br> БЦ Александровский,
                            офис 209 </td>
    
                        </tr>
    
                        <tr>
    
                          <td>ИНН/КПП</td>
    
                          <td>7842447593/784201001</td>
    
                        </tr>
    
                        <tr>
    
                          <td>ОГРН</td>
    
                          <td>1117847062970</td>
    
                        </tr>
    
                        <tr>
    
                          <td>Код по ОКПО</td>
    
                          <td>90774395</td>
    
                        </tr>
    
                        <tr>
    
                          <td>Генеральный директор</td>
    
                          <td>Казанков В.В.</td>
    
                        </tr>
    
                        <tr>
    
                          <td>Телефон</td>
    
                          <td>+7 (812) 407-38-41</td>
    
                        </tr>
    
                        <tr>
    
                          <td>e-mail</td>
    
                          <td>info@legionfirst.ru</td>
    
                        </tr>
    
                        <tr>
    
                          <td>Банковские реквизиты</td>
    
                          <td>СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК, БИК 044030653, <br>
                            Р/сч: № 40702810755100185826, к/с 30101810500000000653</td>
    
                        </tr>
    
                      </tbody>
    
                    </table>
    
                  </div>
    
                </div>
    
              </div>
        </div>
      </div> <!--<div class="row">-->

      <div class="row margin-top-sm">
          <div class="col-sm-12">
              <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a href="#collapse-checkout-option2" data-toggle="collapse" data-parent="#accordion"
                        class="accordion-toggle collapsed" aria-expanded="false">Реквизиты для физических лиц
                        <i class="fa fa-caret-down"></i>
                      </a>
                    </h4>
      
                  </div>
      
                  <div class="panel-collapse collapse" id="collapse-checkout-option2" aria-expanded="false"
                    style="height: 0px;">
                    <div class="panel-body">
                      <table>
      
                        <thead>
                          <tr>
                            <th width="40%"></th>
      
                            <th width="60%"></th>
      
                          </tr>
                        </thead>
      
                        <tbody>
      
                          <tr>
      
                            <td>ИП</td>
      
                            <td>Казанкова Р.Р.</td>
      
                          </tr>
      
                          <tr>
      
                            <td>ИНН</td>
      
                            <td>026508954586</td>
      
                          </tr>
      
                          <tr>
      
                            <td>ОГРНИП</td>
      
                            <td>316784700324106</td>
      
                          </tr>
      
                          <tr>
      
                            <td>Юридический адрес</td>
      
                            <td>192012, г. Санкт-Петербург, пр. Обуховской обороны 138/2</td>
      
                          </tr>
      
                          <tr>
      
                            <td>Фактический адрес</td>
      
                            <td>192012, г. Санкт-Петербург, пр. Обуховской обороны 138/2</td>
      
                          </tr>
      
                          <tr>
      
                            <td>Телефон</td>
      
                            <td>8 921 896 17 86</td>
      
                          </tr>
      
                          <tr>
      
                            <td>e-mail</td>
      
                            <td>zakaz-opps@yandex.ru</td>
      
                          </tr>
      
                          <tr>
      
                            <td>Банковские реквизиты</td>
      
                            <td>ФИЛИАЛ "САНКТ-ПЕТЕРБУРГСКИЙ" АО "АЛЬФА-БАНК”, БИК: 044030786, <br>
                              Р/сч: № 40802810332130002235</td>
      
                          </tr>
      
                        </tbody>
      
                      </table>
      
                    </div>
      
                  </div>
      
                </div>
          </div>
        </div>  <!--<div class="row">-->

      <a href="#feedback"></a>
      <div class="feedback-box">
        <?php echo $feedback; ?>
      </div>

      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>