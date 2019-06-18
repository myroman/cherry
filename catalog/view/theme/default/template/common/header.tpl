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

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
  (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
  m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
  (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(54080398, "init", {
       clickmap:true,
       trackLinks:true,
       accurateTrackBounce:true,
       webvisor:true,
       ecommerce:"dataLayer"
  });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/54080398" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- setup e-commerce -->
<script type="text/javascript">
  window.dataLayer = window.dataLayer || [];
</script>

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
<!-- <?php if ($informations) { ?>
  <div class="container">
    <nav id="menu" class="navbar">
      <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
        <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
      </div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <?php foreach ($informations as $information) { ?>
            <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </nav>
  </div>
<?php } ?> -->

<?php if ($informations) { ?>
  <div class="container">
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
