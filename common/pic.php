<?php
class picMark
{
	var $attachinfo;
	var $targetfile;
	var $imagecreatefromfunc;
	var $imagefunc;
	var $attach;
	var $animatedgif;
	var $watermarkquality;
	var $watermarktext;
	var $thumbstatus;
	var $watermarkstatus;

	function __construct($targetfile, $cfg_thumb, $cfg_watermarktext, $photo_waterpos, $photo_markquality, $photo_wheight, $photo_wwidth, $cfg_watermarktype, $photo_marktrans,$trueMarkimg, $attach = array())
	{
		$this->thumbstatus = $cfg_thumb;
		$this->watermarktext = $cfg_watermarktext;
		$this->watermarkstatus = $photo_waterpos;
		$this->watermarkquality = $photo_markquality;
		$this->watermarkminwidth = $photo_wwidth;
		$this->watermarkminheight = $photo_wheight;
		$this->watermarktype = $cfg_watermarktype;
		$this->watermarktrans = $photo_marktrans;
		$this->animatedgif = 0;
		$this->targetfile = $targetfile;
		$this->attachinfo = @getimagesize($targetfile);
		$this->attach = $attach;
		$this->trueMarkimg=$trueMarkimg;
		switch($this->attachinfo['mime'])
		{
			case 'image/jpeg':
				$this->imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
				$this->imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
				break;
			case 'image/gif':
				$this->imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
				$this->imagefunc = function_exists('imagegif') ? 'imagegif' : '';
				break;
			case 'image/png':
				$this->imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
				$this->imagefunc = function_exists('imagepng') ? 'imagepng' : '';
				break;
		}
		$this->attach['size'] = empty($this->attach['size']) ? @filesize($targetfile) : $this->attach['size'];
		if($this->attachinfo['mime'] == 'image/gif')
		{
			$fp = fopen($targetfile, 'rb');
			$targetfilecontent = fread($fp, $this->attach['size']);
			fclose($fp);
			$this->animatedgif = strpos($targetfilecontent, 'NETSCAPE2.0') === false ? 0 : 1;
		}
	}

	function thumb($thumbwidth, $thumbheight, $preview = 0)
	{
		$this->thumb_gd($thumbwidth, $thumbheight, $preview);

		if($this->thumbstatus == 2 && $this->watermarkstatus)
		{
			$this->image($this->targetfile, $this->attach);
			$this->attach['size'] = filesize($this->targetfile);
		}
	}

	function watermark($preview = 0)
	{
		if($this->watermarkminwidth && $this->attachinfo[0] <= $this->watermarkminwidth && $this->watermarkminheight && $this->attachinfo[1] <= $this->watermarkminheight)
		{
			return ;
		}
		$this->watermark_gd($preview);
	}

	function thumb_gd($thumbwidth, $thumbheight, $preview = 0)
	{

		if($this->thumbstatus && function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled') && function_exists('imagejpeg'))
		{
			$imagecreatefromfunc = $this->imagecreatefromfunc;
			$imagefunc = $this->thumbstatus == 1 ? 'imagejpeg' : $this->imagefunc;
			list($imagewidth, $imageheight) = $this->attachinfo;
			if(!$this->animatedgif && ($imagewidth >= $thumbwidth || $imageheight >= $thumbheight))
			{
				$attach_photo = $imagecreatefromfunc($this->targetfile);
				$x_ratio = $thumbwidth / $imagewidth;
				$y_ratio = $thumbheight / $imageheight;
				if(($x_ratio * $imageheight) < $thumbheight)
				{
					$thumb['height'] = ceil($x_ratio * $imageheight);
					$thumb['width'] = $thumbwidth;
				}
				else
				{
					$thumb['width'] = ceil($y_ratio * $imagewidth);
					$thumb['height'] = $thumbheight;
				}
				$targetfile = !$preview ? ($this->thumbstatus == 1 ? $this->targetfile.'.thumb.jpg' : $this->targetfile) : './watermark_tmp.jpg';
				$thumb_photo = imagecreatetruecolor($thumb['width'], $thumb['height']);
				imagecopyresampled($thumb_photo, $attach_photo, 0, 0, 0, 0, $thumb['width'], $thumb['height'], $imagewidth, $imageheight);
				if($this->attachinfo['mime'] == 'image/jpeg')
				{
					$imagefunc($thumb_photo, $targetfile, 100);
				}
				else
				{
					$imagefunc($thumb_photo, $targetfile);
				}
				$this->attach['thumb'] = $this->thumbstatus == 1 ? 1 : 0;
			}
		}
	}

	function watermark_gd($preview = 0)
	{
		if($this->watermarkstatus && function_exists('imagecopy') && function_exists('imagealphablending') && function_exists('imagecopymerge'))
		{
			$imagecreatefunc = $this->imagecreatefromfunc;
			$imagefunc = $this->imagefunc;
			list($imagewidth, $imageheight) = $this->attachinfo;
			if($this->watermarktype < 2)
			{
				$watermark_file = $this->trueMarkimg;
				$watermarkinfo = @getimagesize($watermark_file);
				$tArr=explode('.',$watermark_file);
				$markFileExt=$tArr[count($tArr)-1];
				$watermark_logo = $markFileExt == 'png' ? @imagecreatefrompng($watermark_file) : @imagecreatefromgif($watermark_file);
				if(!$watermark_logo)
				{
					return ;
				}
				list($logowidth, $logoheight) = $watermarkinfo;
			}
			else
			{
				$box = @imagettfbbox($this->watermarktext['size'], $this->watermarktext['angle'], $this->watermarktext['fontpath'],$this->watermarktext['text']);
				$logowidth = max($box[2], $box[4]) - min($box[0], $box[6]);
				$logoheight = max($box[1], $box[3]) - min($box[5], $box[7]);
				$ax = min($box[0], $box[6]) * -1;
				$ay = min($box[5], $box[7]) * -1;
			}
			$wmwidth = $imagewidth - $logowidth;
			$wmheight = $imageheight - $logoheight;
			if(($this->watermarktype < 2 && is_readable($watermark_file) || $this->watermarktype == 2) && $wmwidth > 10 && $wmheight > 10 && !$this->animatedgif)
			{
				switch($this->watermarkstatus)
				{
					case 1:
						$x = +5;
						$y = +5;
						break;
					case 2:
						$x = ($imagewidth - $logowidth) / 2;
						$y = +5;
						break;
					case 3:
						$x = $imagewidth - $logowidth - 5;
						$y = +5;
						break;
					case 4:
						$x = +5;
						$y = ($imageheight - $logoheight) / 2;
						break;
					case 5:
						$x = ($imagewidth - $logowidth) / 2;
						$y = ($imageheight - $logoheight) / 2;
						break;
					case 6:
						$x = $imagewidth - $logowidth - 5;
						$y = ($imageheight - $logoheight) / 2;
						break;
					case 7:
						$x = +5;
						$y = $imageheight - $logoheight - 5;
						break;
					case 8:
						$x = ($imagewidth - $logowidth) / 2;
						$y = $imageheight - $logoheight - 5;
						break;
					case 9:
						$x = $imagewidth - $logowidth - 5;
						$y = $imageheight - $logoheight -5;
						break;
				}
				$dst_photo = @imagecreatetruecolor($imagewidth, $imageheight);
				$target_photo = $imagecreatefunc($this->targetfile);
				imagecopy($dst_photo, $target_photo, 0, 0, 0, 0, $imagewidth, $imageheight);
				if($this->watermarktype == 1)
				{
					imagecopy($dst_photo, $watermark_logo, $x, $y, 0, 0, $logowidth, $logoheight);
				}
				elseif($this->watermarktype == 2)
				{
					if(($this->watermarktext['shadowx'] || $this->watermarktext['shadowy']) && $this->watermarktext['shadowcolor'])
					{
						$shadowcolorrgb = explode(',', $this->watermarktext['shadowcolor']);
						$shadowcolor = imagecolorallocate($dst_photo, $shadowcolorrgb[0], $shadowcolorrgb[1], $shadowcolorrgb[2]);
						imagettftext($dst_photo, $this->watermarktext['size'], $this->watermarktext['angle'],
						$x + $ax + $this->watermarktext['shadowx'], $y + $ay + $this->watermarktext['shadowy'], $shadowcolor,
						$this->watermarktext['fontpath'], $this->watermarktext['text']);
					}
					$colorrgb = explode(',', $this->watermarktext['color']);
					$color = imagecolorallocate($dst_photo, $colorrgb[0], $colorrgb[1], $colorrgb[2]);
					imagettftext($dst_photo, $this->watermarktext['size'], $this->watermarktext['angle'],
					$x + $ax, $y + $ay, $color, $this->watermarktext['fontpath'], $this->watermarktext['text']);
				}
				else
				{
					imagealphablending($watermark_logo, true);
					imagecopymerge($dst_photo, $watermark_logo, $x, $y, 0, 0, $logowidth, $logoheight, $this->watermarktrans);
				}
				$targetfile = $this->targetfile;
				if($this->attachinfo['mime'] == 'image/jpeg')
				{
					$imagefunc($dst_photo, $targetfile, $this->watermarkquality);
				}
				else
				{
					$imagefunc($dst_photo, $targetfile);
				}
				$this->attach['size'] = filesize($this->targetfile);
			}
		}
	}
}

//检测用户系统支持的图片格式
global $cfg_photo_type,$cfg_photo_typenames,$cfg_photo_support;
$cfg_photo_type['gif'] = false;
$cfg_photo_type['jpeg'] = false;
$cfg_photo_type['png'] = false;
$cfg_photo_type['wbmp'] = false;
$cfg_photo_typenames = Array();
$cfg_photo_support = '';
if(function_exists("imagecreatefromgif") && function_exists("imagegif"))
{
	$cfg_photo_type["gif"] = true;
	$cfg_photo_typenames[] = "image/gif";
	$cfg_photo_support .= "GIF ";
}
if(function_exists("imagecreatefromjpeg") && function_exists("imagejpeg"))
{
	$cfg_photo_type["jpeg"] = true;
	$cfg_photo_typenames[] = "image/pjpeg";
	$cfg_photo_typenames[] = "image/jpeg";
	$cfg_photo_support .= "JPEG ";
}
if(function_exists("imagecreatefrompng") && function_exists("imagepng"))
{
	$cfg_photo_type["png"] = true;
	$cfg_photo_typenames[] = "image/png";
	$cfg_photo_typenames[] = "image/xpng";
	$cfg_photo_support .= "PNG ";
}
if(function_exists("imagecreatefromwbmp") && function_exists("imagewbmp"))
{
	$cfg_photo_type["wbmp"] = true;
	$cfg_photo_typenames[] = "image/wbmp";
	$cfg_photo_support .= "WBMP ";
}

function imageResize($srcFile,$toW,$toH,$toFile="")
{
	global $cfg_photo_type;
	if($toFile=="")
	{
		$toFile = $srcFile;
	}
	$info = "";
	$srcInfo = GetImageSize($srcFile,$info);
	switch ($srcInfo[2])
	{
		case 1:
			if(!$cfg_photo_type['gif'])
			{
				return false;
			}
			$im = imagecreatefromgif($srcFile);
			break;
		case 2:
			if(!$cfg_photo_type['jpeg'])
			{
				return false;
			}
			$im = imagecreatefromjpeg($srcFile);
			break;
		case 3:
			if(!$cfg_photo_type['png'])
			{
				return false;
			}
			$im = imagecreatefrompng($srcFile);
			break;
		case 6:
			if(!$cfg_photo_type['bmp'])
			{
				return false;
			}
			$im = imagecreatefromwbmp($srcFile);
			break;
	}
	$srcW=ImageSX($im);
	$srcH=ImageSY($im);
	//if($srcW<=$toW && $srcH<=$toH ) return true;
	$toWH=$toW/$toH;
	$srcWH=$srcW/$srcH;
	if($toWH<=$srcWH)
	{
		$ftoW=$toW;
		$ftoH=$ftoW*($srcH/$srcW);
	}
	else
	{
		$ftoH=$toH;
		$ftoW=$ftoH*($srcW/$srcH);
	}
	if($srcW>$toW||$srcH>$toH||true)
	{
		if(function_exists("imagecreatetruecolor"))
		{
			@$ni = imagecreatetruecolor($ftoW,$ftoH);
			if($ni)
			{
				imagecopyresampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
			else
			{
				$ni=imagecreate($ftoW,$ftoH);
				imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
		}
		else
		{
			$ni=imagecreate($ftoW,$ftoH);
			imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
		}
		switch ($srcInfo[2])
		{
			case 1:
				imagegif($ni,$toFile);
				break;
			case 2:
				imagejpeg($ni,$toFile,85);
				break;
			case 3:
				imagepng($ni,$toFile);
				break;
			case 6:
				imagebmp($ni,$toFile);
				break;
			default:
				return false;
		}
		imagedestroy($ni);
	}
	imagedestroy($im);
	return true;
}

//图片自动加水印函数
function waterImg($pArr)
{
	$srcFile = $pArr['srcFile'];
	$marktype = $pArr['marktype'] ? $pArr['marktype'] : '1';
	$wwidth = $pArr['wwidth'] ? $pArr['wwidth'] : '120';
	$wheight = $pArr['wheight'] ? $pArr['wheight'] : '120';
	$waterpos = $pArr['waterpos'] ? $pArr['waterpos'] : '7';
	$watertext = $pArr['watertext'] ? $pArr['watertext'] : 'donyu';
	$fontsize = $pArr['fontsize'] ? $pArr['fontsize'] : '20';
	$fontcolor = $pArr['fontcolor'] ? $pArr['fontcolor'] : '0,0,0';
	$marktrans = $pArr['marktrans'] ? $pArr['marktrans'] : '30';
	$markquality = $pArr['markquality'] ? $pArr['markquality'] : '75';
	$markimg = $pArr['markImg'];

	$info = '';
	$srcInfo = @getimagesize($srcFile,$info);
	$srcFile_w= $srcInfo[0];
	$srcFile_h= $srcInfo[1];

	if($srcFile_w < $wwidth || $srcFile_h < $wheight)
	{
		return;
	}

 	$trueMarkimg =SITEROOT.$markimg;
	if(!file_exists($trueMarkimg) || empty($markimg))
	{
		$trueMarkimg = '';
	}
	if($waterpos == 0)
	{
		$waterpos = rand(1, 9);
	}
	$cfg_watermarktext = array();
	if($marktype == '2')
	{
	if(file_exists(SITEROOT.'/data/mark/simhei.ttf'))
	{
		$cfg_watermarktext['fontpath'] =  SITEROOT .'/data/mark/simhei.ttf';
	}
	else
	{
		return ;
	}
	}
	$cfg_watermarktext['text'] = $watertext;
	$cfg_watermarktext['size'] = $fontsize;
	$cfg_watermarktext['angle'] = '0';
	$cfg_watermarktext['color'] = $fontcolor;
	$cfg_watermarktext['shadowx'] = '0';
	$cfg_watermarktext['shadowy'] = '0';
	$cfg_watermarktext['shadowcolor'] = '0,0,0';
	$img = new picMark($srcFile,0, $cfg_watermarktext, $waterpos, $markquality, $wheight, $wwidth, $marktype, $marktrans,$trueMarkimg);
	$img->watermark(0);
}

function imagecreatefrombmp($p_sFile, $saveFile){
	 $file = fopen($p_sFile, "rb");
	 $read = fread($file, 10);
	 while(!feof($file) && ($read <> ""))
	 $read .= fread($file, 1024);
	 $temp = unpack("H*", $read);
	 $hex = $temp[1];
	 $header = substr($hex, 0, 108);
	 if (substr($header, 0, 4) == "424d")
		{
		 $header_parts = str_split($header, 2);
		 $width = hexdec($header_parts[19] . $header_parts[18]);
		 $height = hexdec($header_parts[23] . $header_parts[22]);
		 unset($header_parts);
		 }
	 $x = 0;
	 $y = 1;
	 $image = imagecreatetruecolor($width, $height);
	 $body = substr($hex, 108);
	 $body_size = (strlen($body) / 2);
	 $header_size = ($width * $height);
	 $usePadding = ($body_size > ($header_size * 3) + 4);
	 for ($i = 0;$i < $body_size;$i += 3)
	{
		 if ($x >= $width)
		{
			 if ($usePadding)
				 $i += $width % 4;
			 $x = 0;
			 $y++;
			 if ($y > $height)
				 break;
			 }
		 $i_pos = $i * 2;
		 $r = hexdec($body[$i_pos + 4] . $body[$i_pos + 5]);
		 $g = hexdec($body[$i_pos + 2] . $body[$i_pos + 3]);
		 $b = hexdec($body[$i_pos] . $body[$i_pos + 1]);
		 $color = imagecolorallocate($image, $r, $g, $b);
		 imagesetpixel($image, $x, $height - $y, $color);
		 $x++;
		 }
	 unset($body);
	 imagejpeg($image, $saveFile);
	 }
?>