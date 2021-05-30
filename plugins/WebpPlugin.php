<?php
if ($modx->event->name =='OnFileManagerUpload') {
	$modx->runSnippet('WebPConverter',array(
        'url' => $directory.$files['file']['name']
     ));
}
if ($modx->event->name =='OnFileManagerFileRemove') {
    if(file_exists($path.".webp"))
        unlink($path.".webp");
}
if($modx->event->name == 'OnWebPagePrerender' && stripos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false){
	$content = $modx->Event->params['documentOutput'];     
	$content = &$modx->resource->_output; 
	$imgs = array();
	preg_match_all('/<img[^>]+>/i',$content, $result); 
	if (count($result))
	{
		foreach($result[0] as $img_tag)
		{			
			preg_match('/(src)=("[^"]*")/i',$img_tag, $img[$img_tag]);						
			$img_real = str_replace('"','',$img[$img_tag][2]);
			$img_real = str_replace('./','',$img_real);			
	 	 	 if ((strpos($img_real, '.jpg')!==false) or (strpos($img_real, '.jpeg')!==false) or (strpos($img_real, '.png')!==false)) $imgs[] = $img_real; 					
		}
		$imgs = array_unique($imgs);
		foreach($imgs as $img_real)
		{
		if(($img_real) && (file_exists($modx->config['base_path'].$img_real)))
			{   
			    $img_real = ltrim($img_real, '/');
			    $img_file = pathinfo($img_real);
				$img_webp = $img_file['dirname'] . '/' . $img_file['basename'] . '.webp';
				if (file_exists($img_webp))
    			    $content = str_replace($img_real, $img_webp, $content); 
				else{
				    $img_webp = $modx->runSnippet('WebPConverter',array(
                    'url' => $img_real
                    ));
				}
			}
		}
	}
	preg_match_all('/url\(([^)]*)"?\)/iu', $content, $result);
	$imgs = array();
	if (count($result))
	{
		foreach($result[1] as $img_tag)
		{		
			$img_real = str_replace('./','',$img_tag);			
	 	 	 if ((strpos($img_real, '.jpg')!==false) or (strpos($img_real, '.jpeg')!==false) or (strpos($img_real, '.png')!==false)) $imgs[] = $img_real; 					
		}
		$imgs = array_unique($imgs);
		foreach($imgs as $img_real)
		{
		if(($img_real) && (file_exists($modx->config['base_path'].$img_real)))
			{   
			    $img_real = ltrim($img_real, '/');
			    $img_file = pathinfo($img_real);
				$img_webp = $img_file['dirname'] . '/' . $img_file['basename'] . '.webp';
				
				if (file_exists($img_webp))
    			    $content = str_replace($img_real, $img_webp, $content); 
				else{
				    $img_webp = $modx->runSnippet('WebPConverter',array(
                    'url' => $img_real
                    ));
				}
			}
		}
	}
	$modx->Event->output($content);
}
