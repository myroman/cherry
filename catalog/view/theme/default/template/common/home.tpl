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

    <div class="main-page-info-block banner-wrapper">
      <a href="<?php echo $baseurl ?>index.php?route=product/product&path=57&product_id=50">
        <img class='top-menu-desktop-wrapper' src="<?php echo $desktopBannerUrl ?>" />
        <img class='top-menu-mobile-wrapper' src="<?php echo $mobileBannerUrl ?>" />
      </a>
    </div>

    <div class="more-info info-block">
        <div class="row">
            <div class="col-sm-12 ">
                <h2 class="subheadline">ПРЕИМУЩЕСТВА ВИШНИ</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="col-sm-12">
                    <div class="cherry-features-img">
                        <img src="<?php echo $baseurl ?>image/catalog/cherry-box-white-bg.jpg" />
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="center">
                            <button type="button" class="btn btn-default btn-med btn-buy_green"
                            onclick="cart.add('50', '1', {redirectToUrl: '<?php echo $baseurl; ?>index.php?route=checkout/cart'});">Купить</button>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <?php if ($cherryFeatures) {?>
                    <div class="_cherry-features-ul">    
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
        </div>
    </div>

    <?php if ($usageList) {?>
    <div class="info-block">
        <div class="row">
            <div class="usage-heading">
                    <h2 class="subheadline">ПРИМЕНЕНИЕ</h2>
                </div>
        </div>
        <?php foreach($usageList as $item) { ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-12 usage-text-title">
                            <span class="clr-maroon"><?php echo $item['title'] ?></span>
                        </div>
    
                        <div class="col-sm-12 usage-text-description">
                            <span><?php echo $item['description'] ?></span>
                        </div>
                </div>
                <div class="col-sm-6">
                        <div class="usage-img">
                                <img src="<?php echo $item['imageUrl'] ?>" />
                            </div>
                </div>
            </div>

            <?php } ?>        
    </div>
    <?php } ?>

    <div class="info-block">
        <div class="row">
                <div class="how-works-title">
                        <h2 class="subheadline">Принцип действия</h2>
                    </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
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
                </div>
                
            </div>

            <div class="col-sm-6 mobile-wrapper usage-arrow clr-maroon">
                <i class="fa fa-arrow-down"></i>
            </div>

            <div class="col-sm-6">
                    <div class="how-works-row2">
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
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-sm-12">
                <div class="iframe-full-width-wrapper">
                    <iframe width="100%" height="100%" style="position:absolute; top:0; left: 0" src="https://www.youtube.com/embed/0_sgdTZQigM" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>
                </div>
            </div>
        </div>
    </div>

        <div class="feedback-box">
            <div class="feedback-message">
                <p>
                    Если у Вас остались какие-либо вопросы, свяжитесь с нами для консультации.
                    
                </p>
                <p>
                        Телефон горячей линии: 8 (800) 500-67-46 (звонок бесплатный)
                </p>
            </div>
            <?php echo $feedback; ?>
        </div>
    </div>
    </div>

    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>