<?php
namespace CastorStudio;
class Colors{
    /**
     * Auto darkens/lightens by 10% for sexily-subtle gradients.
     * Set this to FALSE to adjust automatic shade to be between given color
     * and black (for darken) or white (for lighten)
     */
    const DEFAULT_ADJUST = 10;
    
	private $regs = array(
		'hex3'	=> '/^#([a-f\d])([a-f\d])([a-f\d])$/i',
		'hex6'	=> '/^#([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i',
		'hex8'	=> '/^#([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i',
		'rgb'	=> '/^rgb\s*\(\s*([\d\.]+%?)\s*\,\s*([\d\.]+%?)\s*\,\s*([\d\.]+%?)\s*\)$/',
		'rgba'	=> '/^rgba\s*\(\s*([\d\.]+%?)\s*\,\s*([\d\.]+%?)\s*\,\s*([\d\.]+%?)\s*\,\s*([\d\.]+%?)\s*\)$/',
		'hsl'	=> '/^hsl\s*\(\s*([\d\.]+)\s*\,\s*([\d\.]+)%\s*\,\s*([\d\.]+)%s*\)$/',
		'hsla'	=> '/^hsla\s*\(\s*([\d\.]+)\s*\,\s*([\d\.]+)%\s*\,\s*([\d\.]+)%\s*\,\s*([\d\.]+%?)\s*\)$/',
		'cmyk'	=> '/^(?:device-cmyk|cmyk)\s*\(\s*([\d\.]+%?)\s*\,\s*([\d\.]+%?)\s*\,\s*([\d\.]+%?)\s*\,\s*([\d\.]*%?)\s*\)$/'
	);
    
	private $pcent = '/^([\d\.]+)%$/';

	public function toAlpha($color, $alpha, $string){
		$values = (is_array($color)) ? $this->getArrayValues($color) : $this->getValues($color);
        
		if(!$values) return false;

		$alpha = ($alpha > 1) ? $alpha * 0.01 : $alpha;
		$alpha = max( 0, min( 1, $alpha ) );
        
		if($string) return 'rgba(' . $values['r'] . ',' . $values['g'] . ',' . $values['b'] . ','. $alpha .')';
        
		return $values;
	}
    
	//---Convertir a RGB
	public function toRGB($color, $string = false){
        
		$values = (is_array($color)) ? $this->getArrayValues($color) : $this->getValues($color);
        
		if(!$values) return false;
        
		if($string) return 'rgb(' . $values['r'] . ',' . $values['g'] . ',' . $values['b'] . ')';
        
		return $values;
        
	}
    
	//---Convertir a RGBA
	public function toRGBA($color, $string = false){
        
		$values = (is_array($color)) ? $this->getArrayValues($color) : $this->getValues($color);
        
		if(!$values) return false;
        
		if( !isset($values['a']) ) $values['a'] = 1;
        
		if($string) return 'rgba(' . $values['r'] . ',' . $values['g'] . ',' . $values['b'] . ',' . $values['a'] . ')';
        
		return $values;
        
	}
    
	//---Convertir a HEX RGB
	public function toHEX($color, $string = false){
        
		$values = (is_array($color)) ? $this->getArrayValues($color, true) : $this->getValues($color, true);
        
		if(!$values) return false;
        
		if($string) return '#' . $values['r'] . $values['g'] . $values['b'];
        
		return $values;
        
	}
    
	//---Convertir a HEX RGBA
	public function toHEXA($color, $string = false){
        
		$values = (is_array($color)) ? $this->getArrayValues($color, true) : $this->getValues($color, true);
        
		if(!$values) return false;
        
		if( !isset($values['a']) ) $values['a'] = 'FF';
        
		if($string) return '#' . $values['r'] . $values['g'] . $values['b'] . $values['a'];
        
		return $values;
        
	}
    
	//--Convertir a HSL
	public function toHSL($color, $string = false){
        
		$values = $this->toHSLA($color);
        
		if($string) return 'hsl(' . $values['h'] . ',' . $values['s'] . '%,' . $values['l'] . '%)';
        
		unset($values['a']);
        
		return $values;
        
	}
    
	//--Convertir a HSLA
	public function toHSLA($color, $string = false){
        
		$values = (is_array($color)) ? $this->getArrayValues($color) : $this->getValues($color);
        
		if(!$values) return false;
        
		$values['r'] /= 255;
		$values['g'] /= 255;
        $values['b'] /= 255;
        $values['a'] /= 1;
        
		$max = max($values['r'], $values['g'], $values['b']);
		$min = min($values['r'], $values['g'], $values['b']);
        
		$h = 0;
		$s = 0;
		$l = ( $max + $min ) / 2;
        
		$d = $max - $min;
        
		if( $d == 0 ){
            
			$h = 0;
			$s = 0;
			
		}else{
            
			switch( $max ){
                
				case $values['r']:
                
                $h = fmod(($values['g'] - $values['b']) / $d, 6);
                
				break;
                
				case $values['g']:
                
                $h = ($values['b'] - $values['r']) / $d + 2;
                
				break;
                
				case $values['b']:
                
                $h = ($values['r'] - $values['g']) / $d + 4;
                
				break;
                
			}
            
			$h = round($h * 60);
            
			if( $h < 0 ) $h += 360;
            
			$s = $d / ( 1 - abs( 2 * $l - 1) );
            
		}
        
		if($string) return 'hsla(' . $h . ',' . round($s * 100) . '%,' . round($l * 100) . '%,' . $values['a'] . ')';
        
		$values = array(
            
			'h'	=> $h,
			's'	=> round($s * 100),
			'l'	=> round($l * 100),
			'a'	=> $values['a']
            
		);
        
		return $values;
        
	}
    
	//---Convertir a CMYK
	public function toCMYK($color, $string = false){
        
		$values = (is_array($color)) ? $this->getArrayValues($color) : $this->getValues($color);
        
		if(!$values) return false;
        
		$values['r'] /= 255;
		$values['g'] /= 255;
		$values['b'] /= 255;
        
		$k = round((1 - max($values['r'], $values['g'], $values['b'])) * 100);
		$c = round(((1 - $values['r'] - $k) / (1 - $k)) * 100);
		$m = round(((1 - $values['g'] - $k) / (1 - $k)) * 100);
		$y = round(((1 - $values['b'] - $k) / (1 - $k)) * 100);
        
		if($string) return 'device-cmyk(' . $c . '%,' . $m . '%,' . $y . '%,' . $k . '%)';
        
		$values = array(
            
			'c' => $c,
			'm' => $m,
			'y' => $y,
			'k' => $k
            
		);
        
		return $values;
        
	}
    
	//---Convertir un color a objeto
	private function getValues($color, $hex = false){
        
		$values = false;
        
		foreach ($this->regs as $k => $r) {		
			
			if( preg_match($r, $color) ){
				
				$values = array();
                
				switch($k){
                    
					//---Hex RGB case
					case 'hex3':
					case 'hex6':
					case 'hex8':
                    
                    $values['r'] = preg_replace($r, '$1', $color);
                    $values['g'] = preg_replace($r, '$2', $color);
                    $values['b'] = preg_replace($r, '$3', $color);
                    $values['a'] = '1';
                    
                    if($k == 'hex8') $values['a'] = preg_replace($r, '$4', $color);
                    
                    if(!$hex){
                        
                        $values['r'] = $this->getDEC( $values['r'] );
                        $values['g'] = $this->getDEC( $values['g'] );
                        $values['b'] = $this->getDEC( $values['b'] );
                        
                        if($k == 'hex8') $values['a'] = $this->buildAlpha( $this->getDEC(preg_replace($r, '$4', $color)) );
                        
                    }
                    
					break;
                    
					//---RGB case
					case 'rgb':
					case 'rgba':
                    
                    $values['r'] = preg_replace($r, '$1', $color);
                    $values['g'] = preg_replace($r, '$2', $color);
                    $values['b'] = preg_replace($r, '$3', $color);
                    
                    if($k == 'rgba') {
                        
                        $values['a'] = preg_replace($r, '$4', $color);
                        
                        if(preg_match($this->pcent, $values['a'])) {
                            
                            $values['a'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['a']) );
                            
                        }else{
                            
                            $values['a'] = round($values['a'] * 255);
                            
                        }
                        
                        if($values['a'] > 255) $values['a'] = 255;
                        
                    }
                    
                    if(preg_match($this->pcent, $values['r'])) $values['r'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['r']) );
                    if(preg_match($this->pcent, $values['g'])) $values['g'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['g']) );
                    if(preg_match($this->pcent, $values['b'])) $values['b'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['b']) );
                    
                    if($hex){
                        
                        $values['r'] = $this->getHEX( $values['r'] );
                        $values['g'] = $this->getHEX( $values['g'] );
                        $values['b'] = $this->getHEX( $values['b'] );
                        
                        if($k == 'rgba') $values['a'] = $this->getHEX( $values['a'] );
                        
                    }else if( isset($values['a']) ){
                        
                        $values['a'] = $this->buildAlpha( $values['a'] );
                        
                    }
                    
					break;
                    
					//---HSL Case
					case 'hsl':
					case 'hsla':
                    
                    $h = preg_replace($r, '$1', $color);
                    $s = preg_replace($r, '$2', $color);
                    $l = preg_replace($r, '$3', $color);
                    
                    $rgb = $this->hslToRGB($h, $s, $l);
                    
                    $values['r'] = $rgb['r'];
                    $values['g'] = $rgb['g'];
                    $values['b'] = $rgb['b'];
                    
                    if($k == 'hsla') {
                        
                        $values['a'] = preg_replace($r, '$4', $color);
                        
                        if(preg_match($this->pcent, $values['a'])) {
                            
                            $values['a'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['a']) );
                            
                        }else{
                            
                            $values['a'] = round($values['a'] * 255);
                            
                        }
                        
                        if($values['a'] > 255) $values['a'] = 255;
                        
                    }
                    
                    if($hex){
                        
                        $values['r'] = $this->getHEX( $values['r'] );
                        $values['g'] = $this->getHEX( $values['g'] );
                        $values['b'] = $this->getHEX( $values['b'] );
                        
                        if($k == 'hsla') $values['a'] = $this->getHEX( $values['a'] );
                        
                    }else if( isset($values['a']) ){
                        
                        $values['a'] = $this->buildAlpha( $values['a'] );
                        
                    }
                    
					break;
                    
					//---Case CMYK
					case 'cmyk':
                    
                    $c = preg_replace($r, '$1', $color);
                    $m = preg_replace($r, '$2', $color);
                    $y = preg_replace($r, '$3', $color);
                    $k = preg_replace($r, '$4', $color);
                    
                    $rgb = $this->cmykToRGB($c, $m, $y, $k);
                    
                    $values['r'] = $rgb['r'];
                    $values['g'] = $rgb['g'];
                    $values['b'] = $rgb['b'];
                    
                    if($hex){
                        
                        $values['r'] = $this->getHEX( $values['r'] );
                        $values['g'] = $this->getHEX( $values['g'] );
                        $values['b'] = $this->getHEX( $values['b'] );
                        
                    }
                    
					break;
                    
				}
                
				break;
                
			}
            
		}
        
		return $values;
        
	}
    
	//---Convertir un array de color a objeto
	function getArrayValues($color, $hex = false){
        
		//---Tomar las keys
		$code = array_keys($color);
        
		sort($code);
        
		$code = implode('', $code);
        
		//---Array values
		$values = false;
        
		switch($code){
            
			//---RGB
			case 'bgr':
			case 'abgr':
            
            $values = array();
            
            $values['r'] = $color['r'];
            $values['g'] = $color['g'];
            $values['b'] = $color['b'];
            
            if(preg_match($this->pcent, $values['r'])) $values['r'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['r']) );
            if(preg_match($this->pcent, $values['g'])) $values['g'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['g']) );
            if(preg_match($this->pcent, $values['b'])) $values['b'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['b']) );
            
            if($code == 'abgr'){
                
                $values['a'] = $color['a'];
                
                if(preg_match($this->pcent, $values['a'])) $values['a'] = $this->fromPercent( preg_replace($this->pcent, '$1', $values['a']) );
                
            }
            
            if($hex){
                
                $values['r'] = $this->getHEX( $values['r'] );
                $values['g'] = $this->getHEX( $values['g'] );
                $values['b'] = $this->getHEX( $values['b'] );
                
                if($code == 'abgr') $values['a'] = $this->getHEX( $values['a'] );
                
            }else if( isset($values['a']) ){
                
                $values['a'] = $this->buildAlpha( $values['a'] );
                
            }
            
			break;
            
			//---HSL
			case 'hls':
			case 'ahls':
            
            $values = array();
            
            $rgb = $this->hslToRGB(
                $color['h'],
                preg_replace($this->pcent, '$1', $color['s']),
                preg_replace($this->pcent, '$1', $color['l'])
            );
            
            $values['r'] = $rgb['r'];
            $values['g'] = $rgb['g'];
            $values['b'] = $rgb['b'];
            
            if($code == 'ahls') {
                
                if(preg_match($this->pcent, $color['a'])) {
                    
                    $values['a'] = $this->fromPercent( preg_replace($this->pcent, '$1', $color['a']) );
                    
                }else{
                    
                    $values['a'] = round($color['a'] * 255);
                    
                }
                
                if($values['a'] > 255) $values['a'] = 255;
                
            }
            
            if($hex){
                
                $values['r'] = $this->getHEX( $values['r'] );
                $values['g'] = $this->getHEX( $values['g'] );
                $values['b'] = $this->getHEX( $values['b'] );
                
                if($code == 'ahls') $values['a'] = $this->getHEX( $values['a'] );
                
            }else if( isset($values['a']) ){
                
                $values['a'] = $this->buildAlpha( $values['a'] );
                
            }
            
			break;
            
			//---CMYK
			case 'ckmy':
            
            $values = array();
            
            $rgb = $this->cmykToRGB($color['c'], $color['m'], $color['y'], $color['k']);
            
            $values['r'] = $rgb['r'];
            $values['g'] = $rgb['g'];
            $values['b'] = $rgb['b'];
            
            if($hex){
                
                $values['r'] = $this->getHEX( $values['r'] );
                $values['g'] = $this->getHEX( $values['g'] );
                $values['b'] = $this->getHEX( $values['b'] );
                
            }
            
			break;
            
		}
        
		return $values;
        
	}
    
	//---Calcular un valor decimal en base a 255 a partir de un porciento
	private function fromPercent($number){
        
		$value = round( ($number / 100) * 255 );
        
		if($value > 255) return 255;
        
		return $value;
        
	}
    
	//---Calcular un valor decimal entre 0 y 1 a partir de un porciento CMYK
	private function fromCMYKPercent($number){
        
		$value = round( $number / 100, 2 );
        
		if($value > 1) return 1;
        
		return $value;
        
	}
    
	//---Calcular un valor decimal de alpha (entre 0 y 1)
	private function buildAlpha($alpha){
        
		$alpha = round($alpha / 255, 2);
        
		if($alpha > 1) $alpha = 1;
        
		return $alpha;
        
	}
    
	//---Convertir a hexadecimal
	private function getHEX($number){
        
		return str_pad( dechex($number), 2, '0', STR_PAD_LEFT);
        
	}
    
	//---Convertir a decimal
	private function getDEC($hex){
        
		if(strlen($hex) == 1) $hex .= $hex;
        
		return hexdec($hex);
        
	}
    
	//---HSL to RGB
	private function hslToRGB($h, $s, $l){
        
		$h /= 60;
		$s /= 100;
		$l /= 100;
        
		if( $l <= .5 ) {
            
			$t2 = $l * ($s + 1);
            
		} else {
            
			$t2 = $l + $s - ($l * $s);
		}
        
		$t1 = $l * 2 - $t2;
        
		$r = $this->hueToRGB($t1, $t2, $h + 2);
		$g = $this->hueToRGB($t1, $t2, $h);
		$b = $this->hueToRGB($t1, $t2, $h - 2);
        
		return array('r' => $r, 'g' => $g, 'b' => $b);
        
	}
    
	//---HUE to RGB
	private function hueToRGB($t1, $t2, $hue){
        
		if($hue < 0) $hue += 6;
        
		if($hue >= 6) $hue -= 6;
        
		if($hue < 1){
            
			return round((($t2 - $t1) * $hue + $t1) * 255);
            
		}else if($hue < 3){
            
			return round($t2 * 255);
            
		}else if($hue < 4){
            
			return round((($t2 - $t1) * (4 - $hue) + $t1) * 255);
            
		}else{
            
			return round($t1 * 255);
            
		}
        
	}
    
	//---CMYK To RGB
	private function cmykToRGB($c, $m, $y, $k){
        
		if(preg_match($this->pcent, $c)) $c = $this->fromCMYKPercent( preg_replace($this->pcent, '$1', $c) );
		if(preg_match($this->pcent, $m)) $m = $this->fromCMYKPercent( preg_replace($this->pcent, '$1', $m) );
		if(preg_match($this->pcent, $y)) $y = $this->fromCMYKPercent( preg_replace($this->pcent, '$1', $y) );
		if(preg_match($this->pcent, $k)) $k = $this->fromCMYKPercent( preg_replace($this->pcent, '$1', $k) );
        
		$r = round(255 * (1 - $c) * (1 - $k));
		$g = round(255 * (1 - $m) * (1 - $k));
		$b = round(255 * (1 - $y) * (1 - $k));
        
		return array('r' => $r, 'g' => $g, 'b' => $b);
        
    } 
    
    
    
    
    
    
    
    
    
    /**
    * Color Manipulation
    */
    
    /**
    * Given a HEX value, returns a darker color. If no desired amount provided, then the color halfway between
    * given HEX and black will be returned.
    * @param int $amount
    * @return string Darker HEX value
    */
    public function darken($color, $amount = DEFAULT_ADJUST ){
        // Darken
        $hsl = $this->toHSL($color);
        $darkerHSL = $this->_darken($hsl, $amount);
        // Return as HEX
        // return self::hslToHex($darkerHSL);
        return $this->toHEX($darkerHSL,true);
    }
    
    /**
    * Given a HEX value, returns a lighter color. If no desired amount provided, then the color halfway between
    * given HEX and white will be returned.
    * @param int $amount
    * @return string Lighter HEX value
    */
    public function lighten($color, $amount = DEFAULT_ADJUST ){
        // Lighten
        $hsl = $this->toHSL($color);
        $lighterHSL = $this->_lighten($hsl, $amount);
        // Return as HEX
        // return self::hslToHex($lighterHSL);
        return $this->toHEX($lighterHSL,true);
    }
    
    // /**
    // * Given a HEX value, returns a mixed color. If no desired amount provided, then the color mixed by this ratio
    // * @param string $hex2 Secondary HEX value to mix with
    // * @param int $amount = -100..0..+100
    // * @return string mixed HEX value
    // */
    // public function mix($hex2, $amount = 0){
    //     $rgb2 = self::hexToRgb($hex2);
    //     $mixed = $this->_mix($this->_rgb, $rgb2, $amount);
    //     // Return as HEX
    //     return self::rgbToHex($mixed);
    // }
    
    
    // /**
    // * Returns whether or not given color is considered "light"
    // * @param string|Boolean $color
    // * @param int $lighterThan
    // * @return boolean
    // */
    // public function isLight( $color = FALSE, $lighterThan = 130 ){
    //     // Get our color
    //     $color = ($color) ? $color : $this->_hex;
        
    //     // Calculate straight from rbg
    //     $r = hexdec($color[0].$color[1]);
    //     $g = hexdec($color[2].$color[3]);
    //     $b = hexdec($color[4].$color[5]);
        
    //     return (( $r*299 + $g*587 + $b*114 )/1000 > $lighterThan);
    // }
    
    // /**
    // * Returns whether or not a given color is considered "dark"
    // * @param string|Boolean $color
    // * @param int $darkerThan
    // * @return boolean
    // */
    // public function isDark( $color = FALSE, $darkerThan = 130 ){
    //     // Get our color
    //     $color = ($color) ? $color:$this->_hex;
        
    //     // Calculate straight from rbg
    //     $r = hexdec($color[0].$color[1]);
    //     $g = hexdec($color[2].$color[3]);
    //     $b = hexdec($color[4].$color[5]);
        
    //     return (( $r*299 + $g*587 + $b*114 )/1000 <= $darkerThan);
    // }
    
    // /**
    // * Returns the complimentary color
    // * @return string Complementary hex color
    // *
    // */
    // public function complementary() {
    //     // Get our HSL
    //     $hsl = $this->_hsl;
        
    //     // Adjust Hue 180 degrees
    //     $hsl['H'] += ($hsl['H']>180) ? -180:180;
        
    //     // Return the new value in HEX
    //     return self::hslToHex($hsl);
    // }
    
    
    
    // ===========================
    // = Private Functions Below =
    // ===========================
    
    
    /**
    * Darkens a given HSL array
    * @param array $hsl
    * @param int $amount
    * @return array $hsl
    */
    private function _darken( $hsl, $amount = DEFAULT_ADJUST){
        // Check if we were provided a number
        if( $amount ) {
            // $hsl['l'] = ($hsl['l'] * 100) - $amount;
            // $hsl['l'] = ($hsl['l'] < 0) ? 0:$hsl['l']/100;
            $l  = $hsl['l'];
            $_l = $l - $amount;
            $hsl['l'] = ($_l < 0) ? 0 : $_l;
        } else {
            // We need to find out how much to darken
            $hsl['l'] = $hsl['l']/2 ;
        }
        
        return $hsl;
    }
    
    /**
    * Lightens a given HSL array
    * @param array $hsl
    * @param int $amount
    * @return array $hsl
    */
    private function _lighten( $hsl, $amount = DEFAULT_ADJUST){
        // Check if we were provided a number
        if( $amount ) {
            // $hsl['l'] = ($hsl['l'] * 100) + $amount;
            // $hsl['l'] = ($hsl['l'] > 100) ? 1:$hsl['l']/100;
            $l  = $hsl['l'];
            $_l = $l + $amount;
            $hsl['l'] = ($_l > 100) ? 100 : $_l;
        } else {
            // We need to find out how much to lighten
            $hsl['l'] += (1-$hsl['l'])/2;
        }
        
        return $hsl;
    }
    
    // /**
    // * Mix 2 rgb colors and return an rgb color
    // * @param array $rgb1
    // * @param array $rgb2
    // * @param int $amount ranged -100..0..+100
    // * @return array $rgb
    // *
    // * 	ported from http://phpxref.pagelines.com/nav.html?includes/class.colors.php.source.html
    // */
    // private function _mix($rgb1, $rgb2, $amount = 0) {
        
    //     $r1 = ($amount + 100) / 100;
    //     $r2 = 2 - $r1;
        
    //     $rmix = (($rgb1['R'] * $r1) + ($rgb2['R'] * $r2)) / 2;
    //     $gmix = (($rgb1['G'] * $r1) + ($rgb2['G'] * $r2)) / 2;
    //     $bmix = (($rgb1['B'] * $r1) + ($rgb2['B'] * $r2)) / 2;
        
    //     return array('R' => $rmix, 'G' => $gmix, 'B' => $bmix);
    // }
    
}