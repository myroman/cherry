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
    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>
      <?php echo $content_bottom; ?>


    <div class="more-info">        
    
        <div class="cherry-features">
            <div class="cherry-features-heading">
                <h2 class="subheadline">ПРЕИМУЩЕСТВА ВИШНИ</h2>
            </div>
    
            <div class="cherry-features-row2">
                <div class="cherry-features-img-buy-wrap">
                    <div class="cherry-features-img-buy">
                        <div class="cherry-features-img">
                            <img src="<?php echo $baseurl ?>image/catalog/cherry-box-blue-bg.jpg" />
                        </div>
        
                        <div class="cherry-features-buy">
                            <button type="button" class="btn btn-default btn-med btn-green">Купить</button>
                        </div>
                    </div>
                </div>
    
                <?php if ($cherryFeatures) {?>
                    <div class="cherry-features-ul">
    
                        <ul>
                            <?php foreach($cherryFeatures as $item) { ?>
                            <li class="cherry-features-li">
                                <div class="cherry-features-li-title clr-maroon">
                                    <i class="fa fa-check-circle"></i>
                                    <span class="clr-maroon"><?php echo $item['title'] ?></span>
                                </div>
            
                                <div class="cherry-features-li-description">
                                    <p>
                                        <?php echo $item['description'] ?>
                                    </p>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>                
                    </div>
                <?php } ?>
            </div>
        </div> <!--cherry-features-->
    
        <?php if ($usageList) {?>
        <div class="usage">
            <div class="usage-heading">
                <h2 class="subheadline">ПРИМЕНЕНИЕ</h2>
            </div>
    
            <div class="usage-content">
                <ul>
                    <?php foreach($usageList as $item) { ?>
                    <li>
                        <div class="usage-content-row">
                            <div class="usage-text">
                                <div class="usage-text-title">
                                    <span class="clr-maroon"><?php echo $item['title'] ?></span>
                                </div>
            
                                <div class="usage-text-description">
                                    <span><?php echo $item['description'] ?></span>
                                </div>
                            </div>
            
                            <div class="usage-img">
                                <img src="<?php echo $item['imageUrl'] ?>" />
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    
        <?php } ?>
    
        <div class="how-works">
            <div class="how-works-title">
                <h2 class="subheadline">Принцип действия</h2>
            </div>
            <div class="how-works-row2">
                <div class="how-works-img">
                    <img src="<?php echo $baseurl ?>image/catalog/how-works-1.jpg" />
                </div>
                <div class="how-works-description how-works-description1">
                  <p>
                      При попадании огнетушителя ВИШНЯ в огонь происходит воспламенение 
                    запального шнура на внешней поверхности корпуса с последующим инициированием 
                    размещенного внутри пиротехнического заряда
                  </p>
                </div>
                <div class="filler"></div>
                <div class="how-works-img">
                    <img src="<?php echo $baseurl ?>image/catalog/how-works-2.jpg" />
                </div>
                <div class="how-works-description how-works-description2">
                    <p>
                            Это приводит к вскрытию корпуса огнетушителя 
                            и равномерному распределению огнетушащего вещества по площади или объему горения
                    </p>
                </div>
            </div>
        </div>

        <div class="feedback-box">
            <div class="feedback-message">
                <p>
                    Если у Вас остались какие-либо вопросы, свяжитесь с нами для консультации.
                    
                </p>
                <p>
                        Телефон горячей линии: 8 800 500-67-46 (звонок бесплатный)
                </p>
            </div>
    
            <div class="feedback-header">
                <h2 class="subheadline">
                    ФОРМА ОБРАТНОЙ СВЯЗИ
                </h2>
            </div>
    
            <div class="feedback-form">
                <form>
                    <div>
                        <input type="text" name="fullname" placeholder="Имя*" />
                    </div>
    
                    <div>
                        <input type="email" name="email" placeholder="E-mail*" />
                    </div>
    
                    <div>
                        <input type="tel" name="phone" placeholder="Телефон" />
                    </div>
    
                    <div>
                        <textarea name="message" maxlength="1000" placeholder="Сообщение*" rows="5"></textarea>
                    </div>
    
                    <div class="submit-button-wrapper">
                        <button type="button" class="btn btn-default btn-med btn-green">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>