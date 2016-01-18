<?

// Mandelbrot Klasse
class fraktal
{
    var $startRe;
    var $startIm;    
    var $endeRe;
    var $endeIm;
    var $zRe;
    var $zIm;
    var $stepsRe;
    var $stepsIm;

    var $iterations;
    var $size_x;
    var $size_y;
    var $pal_startcolor;
    var $pal_endcolor;
     
    var $pixelblock = array();
    
    var $iMatrix = array();
    var $dim = 16;        
    var $len = 20;
    var $height = 10;

    var $preset = array ( '0'=> array(),
                          '1'=> array(-2.5,-1.5,1.5,1.5),
                          '2'=> array(-1.3163,-0.4173,-1.0836,-0.1847),
                          '3'=> array(-1.23854466281,-0.390617339835,-1.26586983793,-0.3760361853),
			  '4'=> array(-1.72382375,0.0581391,-1.1572953125,-0.16465005),
			  '5'=> array(-1.36972842709,-0.0705535683853,-1.36902783803,-0.0708322070722)
                          );
                          
    var $color = array (    '0' => array ( 0x2020FF,0xFFFFFF ),
                            '1' => array ( 0xff8a00,0x0000ff ),
                            '2' => array ( 0xFF2020,0xfff000 ),
                            '3' => array ( 0xFFFF00,0xff0000 ),
                            '4' => array ( 0x20FF20,0xff0000 ),                            
                            '5' => array ( 0x833d1a,0xff0000 ),
                            '6' => array ( 0x000000,0xFFFFFF ),
                        );
                        
    // Konstruktor
    function fraktal()
    {
        // Komplexe Zahl Z, bestehend aus Real- und Imaginärteil (defaults)
        // (Ausgangspunkt/Endpunkt der Berechnungen festlegen)
        $this->startRe = (double)-2.5;
        $this->startIm = (double)-1.5;
        
        $this->endeRe = (double)1.5;
        $this->endeIm = (double)1.5;
        
        // Mit complex->z wird gerechnet
        $this->zRe = (double)0;
        $this->zIm = (double)0;
        
        // Eigenschaften festlegen (defaults)
        $this->iterations = (integer)30;
        $this->size_x = 320;
        $this->size_y = 200;
        
        // Farbverlauf: Startfarbe & Endfarbe
        $this->pal_startcolor = 0x2020FF;
        $this->pal_endcolor = 0xFF2020; 
        
        // Punkte des Bildes berechnen
        for ($y = 0; $y < $this->size_y; $y++)
	{
            for ($x = 0; $x < $this->size_x; $x++)
	    {     
                $this->iMatrix[$y][$x] = array('x'=> $x,'y'=> $y);
            }
        }
        
        foreach ($this->iMatrix as $k => $v)
	{
            for ($i=0;$i<$this->dim;$i++)
	    {
                for ($j=$i*$this->len;$j<($i+1)*$this->len;$j++)
		{
                    $this->pixelblock[$k][$i][]=$v[$j];
                }            
            }
        } 
        unset($this->iMatrix);
    }

    // Mandelbrot Fraktal berechnen
    function render_mandelbrot()
    {    
        $tStart = microtime(true);
        
        // Schrittweite der komplexen Punkte berechnen
        $this->stepsRe = (double)((($this->startRe * -1) + ($this->endeRe)) / ($this->size_x-1));
        $this->stepsIm = (double)((($this->startIm * -1) + ($this->endeIm)) / ($this->size_y-1));
        
        // Startpunkt als ersten Berechnungspunkt festlegen
        $this->zRe = $this->startRe;
        $this->zIm = $this->startIm ;        
    
        // Startwert der X-Achse (Realteil der komplexen Zahl) speichern
        $re_start = $this->zRe;
        
        // Bild erzeugen
        $image = imagecreatetruecolor($this->size_x, $this->size_y+$this->len);
        if (!$image)
	{
            $image = imagecreate ($this->size_x, $this->size_y+$this->len);        
        }
        
        // Palette erzeugen       
        $palette = $this->image_createpalette( $image, $this->pal_startcolor, $this->pal_endcolor );
        
        $skip = 0;
        
        // Punkte des Bildes berechnen
        for( $row = 0; $row < $this->height; $row++ )
        {          
            for( $d = 0; $d < $this->dim; $d++ )
            {   
                $this->zRe = $this->startRe;  
                $this->zIm = $this->startIm+$this->stepsIm*($row*$this->len); 

                $cgeTop=0;
                $cgeLeft=0;
                $cgeRight=0;
                $cgeBottom=0;                
                
                $this->zRe = (double)($re_start+$this->stepsRe*$d*$this->len);
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);
                if($steps_done < $this->iterations)
		{
                    $cgeTop++;
                    $cgeLeft++;
                }
                $this->zRe += $this->stepsRe*$this->height;
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);
                if($steps_done < $this->iterations)
		{
                    $cgeTop++; 
                }                
                $this->zRe += $this->stepsRe*$this->height;
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);
                if($steps_done < $this->iterations)
		{
                    $cgeTop++;
                    $cgeRight++;
                }                
               
                $this->zRe = (double)($re_start+$this->stepsRe*$d*$this->len);
                $this->zIm = (double)$this->startIm+$this->stepsIm*($row*$this->len)+$this->stepsIm*10; 
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);
                if( $steps_done < $this->iterations )
		{
                    $cgeLeft++;
                }
                
                $this->zRe += $this->stepsRe*$this->len;
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);
                if( $steps_done < $this->iterations )
		{
                    $cgeRight++;
                }
                
                $this->zRe = (double)($re_start+$this->stepsRe*$d*$this->len);
                $this->zIm = (double)$this->startIm+$this->stepsIm*($row*$this->len)+$this->stepsIm*$this->len; 
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);                
                if($steps_done < $this->iterations)
		{
                    $cgeBottom++;
                    $cgeLeft++;
                }
                $this->zRe += $this->stepsRe*$this->height;
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);                
                if($steps_done < $this->iterations)
		{
                    $cgeBottom++; 
                }
                $this->zRe += $this->stepsRe*$this->height;
                $steps_done = $this->complex_iterate($this->zRe,$this->zIm);
                if($steps_done < $this->iterations)
		{
                    $cgeBottom++;
                    $cgeRight++;                    
                }
               
                $b1 = array();
                for ($i=($row*$this->len);$i<($row+1)*$this->len+1;$i++)
		{
                    for ($j=0;$j<$this->len;$j++)
		    {
                        $b1[$i][] = $this->pixelblock[$i][$d][$j];
                    }
                }
                
                $this->zRe = $this->startRe; 
                $this->zIm = $this->startIm+$this->stepsIm*($row*$this->len);  
                
                if ($cgeTop > 1 || $cgeBottom > 1 || $cgeRight > 1 || $cgeLeft > 1)
		{            
                    foreach ($b1 as $k => $v)
		    {    
                        $this->zRe = (double)($re_start+$this->stepsRe*$d*$this->len);
                        
                        foreach ($v as $k1 => $v1)
			{
                            $steps_done = $this->complex_iterate($this->zRe,$this->zIm);                                       
                           
                            // Wurde die Iteration abgebrochen, ist der Punkt nicht innerhalb der Mandelbrot-Menge
                            if( $steps_done < $this->iterations )
			    {
                                ImageSetPixel( $image, $v1['x'], $v1['y'], $palette[$steps_done] );
                            } else
			    {                                
                                ImageSetPixel( $image, $v1['x'], $v1['y'], 0x000000 );
                            }
                            $this->zRe += $this->stepsRe;
                        }                        
                        $this->zIm += $this->stepsIm;
                    }
                } else
		{
                    $skip++;
                }
            }
        }
        
        $runTime = (microtime(true)-$tStart);
        
        ob_start();        
        $string = "Time: ".round($runTime,2)." Sec. Skip: $skip Blocks"; 
        $orange = imagecolorallocate($image, 220, 210, 60);
        $px = (imagesx($image) - 7.5 * strlen($string)) / 2;
        imagestring($image, 3, $px, 203, $string, $orange);        
        ImagePNG($image);                
        $imagevariable = ob_get_contents();        
        ImageDestroy($image);
        ob_end_clean();
        
        return $imagevariable;
    }

    function complex_iterate($re,$im)
    {    
        // Iteration durchführen
        $cmplx_zIm = $cmplx_zRe = 0;
        
        for( $i = 0; $i < $this->iterations; $i++ )
        {    
            // Zwei Komplexe Zahlen multiplizieren
            $cmplx_zqRe = (double)(($cmplx_zRe + $cmplx_zIm) * ($cmplx_zRe - $cmplx_zIm));
            $cmplx_zqIm = (double)((2 * $cmplx_zRe * $cmplx_zIm));
        
            $cmplx_zRe = $cmplx_zqRe + $re;
            $cmplx_zIm = $cmplx_zqIm + $im;
            
            // Komplexe Zahl als reale Zahl auswerten
            if($cmplx_zRe*$cmplx_zRe+$cmplx_zIm*$cmplx_zIm > 4)
	    {            
                break;
            }
        }
        return $i;
    }

    // Verlaufs-Palette erzeugen
    function image_createpalette( &$image, $start_color, $end_color )
    {
        $palette = array();   
        
        // Die Aditionswerte ermitteln
        $add_r = ceil( ((($end_color & 0xFF0000) >> 16) - (($start_color & 0xFF0000) >> 16)) / $this->iterations);
        $add_g = ceil( ((($end_color & 0x00FF00) >> 8) - (($start_color & 0x00FF00) >> 8)) / $this->iterations);        
        $add_b = ceil( (($end_color & 0x0000FF) - ($start_color & 0x0000FF)) / $this->iterations);
        
        // RGB mit binärem UND errechnen
        $r = ($start_color & 0xFF0000) >> 16;
        $g = ($start_color & 0x00FF00) >> 8;
        $b = $start_color & 0x0000FF;
        
        for( $i = 0; $i < $this->iterations; $i++ )
        {
            $palette[] = ImageColorAllocate( $image, $r, $g, $b );
            $r += $add_r;
            $g += $add_g;
            $b += $add_b;
        }
        return $palette;
    }
}

?>
