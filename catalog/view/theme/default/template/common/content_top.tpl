<?php foreach ($modules as $module) { ?>
<?php echo $module; ?>
<?php } ?>


<div class="more-info">
    <div class="how-works">
        <div class="how-works-title">
            <h2 class="subheadline">Принцип действия</h2>
        </div>
        <div class="how-works-row2">
            <div class="how-works-img">
                <img src="/cherry/image/catalog/how-works-1.jpg" />
            </div>
            <div class="how-works-description how-works-description1">
                    <p>При попадании огнетушителя ВИШНЯ в огонь происходит воспламенение 
                    запального шнура на внешней поверхности корпуса с последующим инициированием 
                    размещенного внутри пиротехнического заряда
                </p>
            </div>
            <div class="filler"></div>
            <div class="how-works-img">
                <img src="/cherry/image/catalog/how-works-2.jpg" />
            </div>
            <div class="how-works-description how-works-description2">
                <p>
                        Это приводит к вскрытию корпуса огнетушителя 
                        и равномерному распределению огнетушащего вещества по площади или объему горения
                </p>
            </div>
        </div>
    </div>

    <div class="cherry-features">
        <div class="cherry-features-heading">
            <h2 class="subheadline">ПРЕИМУЩЕСТВА ВИШНИ</h2>
        </div>

        <div class="cherry-features-row2">
            <div class="cherry-features-img-buy-wrap">
                <div class="cherry-features-img-buy">
                    <div class="cherry-features-img">
                        <img src="/cherry/image/catalog/cherry-box-blue-bg.jpg" />
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
                            <div class="cherry-features-li-title">
                                <i class="fa fa-check-circle"></i>
                                <?php echo $item['title'] ?>
                            </div>
        
                            <div class="cherry-features-li-description">
                                <?php echo $item['description'] ?>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>                
                </div>
            <?php } ?>
        </div>
    </div>
</div>