<?

header("Expires: Sat, 1 Jan 2005 00:00:00 GMT");
header("Last-Modified: ".gmdate( "D, d M Y H:i:s")."GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

require_once('mandelbrot.php');

$frobj = new fraktal();
$frobj->fraktal();

$frobj->pal_startcolor = $frobj->color[$_GET['color']][0];
$frobj->pal_endcolor = $frobj->color[$_GET['color']][1]; 


if ($_GET['cmd'] == 'render' && $_GET['preset'] == '0') {

    $frobj->startRe = (double)($_GET['startRe']);
    $frobj->startIm = (double)($_GET['startIm']);
    $frobj->endeRe = (double)($_GET['endeRe']);
    $frobj->endeIm = (double)($_GET['endeIm']);        

    $frobj->stepsRe = (double)((($_GET['startRe'] * -1) + $_GET['endeRe']) / $frobj->size_x);
    $frobj->stepsIm = (double)((($_GET['startIm'] * -1) + $_GET['endeIm']) / $frobj->size_x);
} 

if ($_GET['cmd'] == 'render' && $_GET['preset'] != '0') {

    $frobj->startRe = (double)($frobj->preset[$_GET['preset']][0]);
    $frobj->startIm = (double)($frobj->preset[$_GET['preset']][1]);
    $frobj->endeRe = (double)($frobj->preset[$_GET['preset']][2]);
    $frobj->endeIm = (double)($frobj->preset[$_GET['preset']][3]);     

    $frobj->stepsRe = (double)((($frobj->startRe * -1) + $frobj->endeRe) / $frobj->size_x);
    $frobj->stepsIm = (double)((($frobj->startIm * -1) + $frobj->endeIm) / $frobj->size_x);
} 

if ($_GET['cmd'] == 'zoomIn') {
    
    $zoom = 20;
    //
    //$frobj->stepsRe = $_GET['stepsRe']*$zoom;
    //$frobj->stepsIm = $_GET['stepsIm']*$zoom;
    //
    //$xDown = $_GET['input_crop_x']+floor($_GET['input_crop_width']/2);
    //$yDown = $_GET['input_crop_y']+floor($_GET['input_crop_height']/2);
    //    
    //$t1 = (160-$xDown)*$frobj->stepsRe;
    //$t2 = ($_GET['stepsRe']-$frobj->stepsRe) * $xDown;    
    //
    //$frobj->startRe = $_GET['startRe']-$t1-$t2;  
    //
    //$t1 = (100-$yDown)*$frobj->stepsIm;
    //$t2 = ($_GET['stepsIm']-$frobj->stepsIm) * $yDown;
    //
    //$frobj->startIm = $_GET['startIm']-$t1-$t2;  
    //
    //$t1 = (160-$xDown)*$frobj->stepsRe;
    //$t2 = ($_GET['stepsRe']-$frobj->stepsRe) * $xDown;
    //
    //$frobj->endeRe = $_GET['endeRe']-$t1+$t2;
    //
    //$t1 = (100-$yDown)*$frobj->stepIm;
    //$t2 = ($_GET['stepsIm']-$frobj->stepsIm) * $yDown;
    //
    //$frobj->endeIm = $_GET['endeIm']-$t1+$t2;  
    
            // Schrittweite der komplexen Punkte berechnen
    $frobj->stepsRe = (double)((($_GET['startRe'] * -1) + $_GET['endeRe']) / ($frobj->size_x-1));
    $frobj->stepsIm = (double)((($_GET['startIm'] * -1) + $_GET['endeIm']) / ($frobj->size_y-1));
  
    $h = $frobj->size_y/$frobj->size_x*($_GET['input_crop_width']);          
  
    $frobj->startRe = $_GET['startRe']+$frobj->stepsRe*($_GET['input_crop_x']);  
    $frobj->endeRe = $_GET['startRe']+$frobj->stepsRe*($_GET['input_crop_x']+$_GET['input_crop_width']);
    $frobj->startIm = $_GET['startIm']+$frobj->stepsIm*($_GET['input_crop_y']); 
    $frobj->endeIm = $_GET['startIm']+$frobj->stepsIm*($_GET['input_crop_y']+$h);

    $frobj->stepsRe = (double)((($frobj->startRe * -1) + ($frobj->endeRe)) / ($frobj->size_x-1));
    $frobj->stepsIm = (double)((($frobj->startIm * -1) + ($frobj->endeIm)) / ($frobj->size_y-1));
   
    $frobj->iterations = 60;
  
 }         

if ($_GET['cmd'] == 'zoomOut') {

#    $zoom = 0.00001;

#    $frobj->stepsRe = $_GET['stepsRe']*$zoom;
#    $frobj->stepsIm = $_GET['stepsIm']*$zoom;
    
#    $xDown = $_GET['input_crop_x']+floor($_GET['input_crop_width']/2);
#    $yDown = $_GET['input_crop_y']+floor($_GET['input_crop_height']/2);
        
#    $t1 = (320-$xDown)*$frobj->stepsRe;
#    $t2 = ($_GET['stepsRe']-$frobj->stepsRe) * $xDown;
    
#    $frobj->startRe = $_GET['startRe']-$t1-$t2;  
    
#    $t1 = (200-$yDown)*$frobj->stepsIm;
#    $t2 = ($_GET['stepsIm']-$frobj->stepsIm) * $yDown;
    
#    $frobj->startIm = $_GET['startIm']-$t1-$t2;  
    
#    $t1 = (320-$xDown)*$frobj->stepsRe;
#    $t2 = ($_GET['stepsRe']-$frobj->stepsRe) * $xDown;
    
#    $frobj->endeRe = $_GET['endeRe']-$t1+$t2;

#    $t1 = (200-$yDown)*$frobj->stepIm;
#    $t2 = ($_GET['stepsIm']-$frobj->stepsIm) * $yDown;
    
#    $frobj->endeIm = $_GET['endeIm']-$t1+$t2;  


          // Schrittweite der komplexen Punkte berechnen
    $frobj->stepsRe = (double)((($_GET['startRe'] * -1) + $_GET['endeRe']) / ($frobj->size_x-1));
    $frobj->stepsIm = (double)((($_GET['startIm'] * -1) + $_GET['endeIm']) / ($frobj->size_y-1));

    $h = $frobj->size_y/$frobj->size_x*$_GET['input_crop_width'];

    $frobj->startRe = $_GET['startRe']-$frobj->stepsRe*(320-$_GET['input_crop_x']);
    $frobj->endeRe = $_GET['endeRe']+$frobj->stepsRe*($_GET['input_crop_x']+$_GET['input_crop_width']);
    $frobj->startIm = $_GET['startIm']-$frobj->stepsIm*(200-$_GET['input_crop_y']);
    $frobj->endeIm = $_GET['endeIm']+$frobj->stepsIm*($_GET['input_crop_y']+$h);

    $frobj->stepsRe = (double)((($frobj->startRe * -1) + ($frobj->endeRe)) / ($frobj->size_x-1));
    $frobj->stepsIm = (double)((($frobj->startIm * -1) + ($frobj->endeIm)) / ($frobj->size_y-1));
    
    $frobj->iterations = 60;

}         
   
    
$im = $frobj->render_mandelbrot(); 

$filename = 'typo3temp/mandelbrot_'.substr(md5(time().'sjHd32a'), 0,7).'.png';

if (!$handle = fopen($filename, "wb")) {
     print "Kann die Datei $filename nicht öffnen";
     exit;
}

// Schreibe $somecontent in die geöffnete Datei.
if (!fwrite($handle, $im)) {
   print "Kann in die Datei $filename nicht schreiben";
   exit;
} 
fclose($handle);

// XHTML repsonse
echo $filename.'|'.$frobj->startRe.'|'.$frobj->startIm.'|'.$frobj->endeRe.'|'.$frobj->endeIm.'|'.$frobj->stepsRe.'|'.$frobj->stepsIm;


?>
