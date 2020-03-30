<?php

	$synclistnumber = '';
    $myargv = $_SERVER['argv'];
	
	// Change Synclist, Change TimeZone, add API
	date_default_timezone_set('Europe/Moscow');
	require_once( 'lib/woocommerce-api.php' );
	require_once( 'synclist-'.$myargv[1].'.php' );

//    require_once( 'synclist-x.php' );
    
	$consumer_key_to = 'ck_530c854d9ece6ffd4sdfsdfsdf5264cb7369064fd3e12771e';
	$consumer_secret_to = 'cs_4cea36c123c4c2cb055sdfsdfsdf4ccabc5b891abfa72febe';
	
	$options = array(
		'debug'           => false,
		'return_as_array' => true,
		'validate_url'    => false,
		'timeout'         => 300,
		'ssl_verify'      => false,
	);
	
	$site_to = new WC_API_Client( 'https://example.com', $consumer_key_to, $consumer_secret_to, $options );
	
	foreach($synclist as $phone => $ids)
	{
		$instockclick=0;
		$outstockclick=0;
		foreach($ids  as $to)
		{
		
	
	
			// Обновление видимости
			
			$currentproduct =$site_to->products->get($to);
			$currentproductstock = $currentproduct['product']['stock_quantity'];
			$currentvisibility =$currentproduct['product']['catalog_visibility'];
			$site_to->products->update( $to, array( 'catalog_visibility' => 'search' ) );
			
				// Есть в наличии
			
				if ($currentproductstock > 0) {
					$instockclick++;
					if ($instockclick==1) {
						$site_to->products->update( $to, array( 'catalog_visibility' => 'visible' ) );
					}
				}
				
				// Нет в Наличии
				
				if ($currentproductstock <= 0) {
					$outstockclick++;
					if ($outstockclick ==count($ids)) {
						$site_to->products->update( $to, array( 'catalog_visibility' => 'visible' ) );
					}
				}
			
		}
		
	
	};

