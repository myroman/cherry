<footer class="my-footer">
  <div class="container">
    <div class="row">

      <?php if ($informations) { ?>
        <div class="col-sm-2 col-sm-push-4 pad-column footer-block">
          <h5><?php echo $text_information; ?></h5>
          <ul class="list-unstyled">
            <?php foreach ($informations as $information) { ?>
            <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
        <div class="col-sm-2 col-sm-push-4 pad-column footer-block">
          <h5><?php echo $text_service; ?></h5>
          <ul class="list-unstyled">
            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
            <li><a href="<?php echo $delivery_and_payment; ?>" title="Доставка и оплата"> <span>Доставка и оплата</span></a></li>
          <li><a href="<?php echo $refunds; ?>" title="Возврат"><span>Возврат</span></a></li>
          </ul>
        </div>

      <div class="col-sm-4 col-sm-pull-4 footer-block">
          <div id="logo" class="top-action-logo desktop-wrapper">
              <?php if ($logo) { ?>
              <a href="<?php echo $home; ?>">
                <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" 
                alt="<?php echo $name; ?>" class="img-responsive" />
              </a>
              <?php } else { ?>
              <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
              <?php } ?>
          </div>

          <p>
              © ООО “Первый Легион” | 2019
              <br>
              Все права защищены.
              <br>
               Продукция запатентована. Полное или 
               <br>
               частичное копирование материалов сайта в 
               <br>
               коммерческих целях разрешено только с 
               <br>
               письменного согласия ООО Первый Легион.
          </p>
      </div>

      <div class="col-sm-3 pad-column-contacts desktop-wrapper">
          <div class="footer-contacts">
              <div class="footer-contacts-bl">
                  <a href="tel:8 800 500-67-46">8 800 500-67-46</a> (звонок бесплатный)
              </div>

              <div class="footer-contacts-bl">
                  <div>Центральный офис:</div>
                  <div>192012, Санкт-Петербург, пр. Обуховской об, 120 лит. Л</div>
              </div>

              <div class="footer-contacts-bl">
                  <div>тел: +7 (812) 407-38-41</div>
                  <div>e-mail: info@legionfirst.ru</div>
              </div>
          </div>
      </div>
    </div>
    
  </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

</body></html>