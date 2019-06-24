<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta name="yandex-verification" content="473bb303f2c8725a" />
<meta name="google-site-verification" content="E6seqrpPFKd4Ek4yy1yG83IyDZ6C0KV-GHVQVc6iYZQ" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<link href="catalog/view/theme/default/stylesheet/custom.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>

</head>
<body class="<?php echo $class; ?>">
<nav id="top">
  <div class="container">
    <?php echo $currency; ?>
    <?php echo $language; ?>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
        <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $delivery_and_payment; ?>" title="Доставка и оплата"> <span class="hidden-xs hidden-sm hidden-md">Доставка и оплата</span></a></li>
        <li><a href="<?php echo $refunds; ?>" title="Возвраты"><span class="hidden-xs hidden-sm hidden-md">Возвраты</span></a></li>
      </ul>
    </div>
  </div>
</nav>
<header>
    <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="top-action-info-block">
                <div id="logo" class="top-action-logo">
                    <?php if ($logo) { ?>
                    <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
                    <?php } else { ?>
                    <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
                    <?php } ?>
                </div>
    
                <div class="top-action">
                    <button type="button" class="btn btn-default btn-med btn-buy_green" 
                    onclick="cart.add('50', '1', {redirectToUrl: '<?php echo $baseurl; ?>index.php?route=checkout/cart'});">Купить</button>
                </div>
    
                <div class="top-cart">
                    <?php echo $cart; ?>
                </div>
    
                <div class="top-info">
                    <div class="top-info-phone">8 800 500 67 46</div>
                    <div class="top-info-shipinfo">
                      <div>Бесплатный звонок</div>  
                      <div>Доставка по всей России</div>    
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
</header>

<?php if ($informations) { ?>
  <div class="top-menu-mobile-wrapper">
    <nav class="navbar navbar-default top-menu">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topcollapse-menu" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>" class="top-cart">
            <i class="fa fa-shopping-cart"></i>
          </a>

          <?php if ($logo) { ?>
            <a href="<?php echo $home; ?>" class="navbar-brand"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
            <?php } else { ?>
            <h1><a href="<?php echo $home; ?>" class="navbar-brand"><?php echo $name; ?></a></h1>
            <?php } ?>
        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="topcollapse-menu">
          <ul class="nav navbar-nav">
            <?php foreach ($informations as $information) { ?>
                <li class="top-menu-item"> 
                    <a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a>
                </li>
              <?php } ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
  </div>
  
  <div class="container top-menu-desktop-wrapper">
    <nav id="top-menu">      
        <ul class="top-menu-list">
          <?php foreach ($informations as $information) { ?>
            <li class="top-menu-item"> 
              <div >
                  <a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a>
              </div>
            </li>
          <?php } ?>
        </ul>
    </nav>
  </div>
<?php } ?>
