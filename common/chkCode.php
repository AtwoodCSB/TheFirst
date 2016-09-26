<?php
class chkCodeCls
{
//验证码位数
public $mCheckCodeNum   = 4;
//产生的验证码
public $mCheckCode    = '';
//验证码的图片
private $mCheckImage   = '';
//干扰像素
private $mDisturbColor   = '';
//验证码的图片宽度
private $mCheckImageWidth = '80';
//验证码的图片宽度
private $mCheckImageHeight   = '20';
private function OutFileHeader()
{
   header ("Content-type: image/png");
}
private function CreateCheckCode()
{
   return $this->mCheckCode;
}
private function CreateImage()
{
   $this->mCheckImage = @imagecreate ($this->mCheckImageWidth,$this->mCheckImageHeight);
   imagecolorallocate ($this->mCheckImage, 200, 200, 200);
   return $this->mCheckImage;
}
private function SetDisturbColor()
{
   for ($i=0;$i<=128;$i++)
   {
    $this->mDisturbColor = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,255), rand(0,255));
    imagesetpixel($this->mCheckImage,rand(2,128),rand(2,38),$this->mDisturbColor);
   }
}
public function SetCheckImageWH($width,$height)
{
   if($width==''||$height=='')return false;
   $this->mCheckImageWidth   = $width;
   $this->mCheckImageHeight = $height;
   return true;
}
private function WriteCheckCodeToImage()
{
   for ($i=0;$i<=$this->mCheckCodeNum;$i++)
   {
    $bg_color = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,128), rand(0,255));
    $x = floor($this->mCheckImageWidth/$this->mCheckCodeNum)*$i;
    $y = rand(0,$this->mCheckImageHeight-15);
    imagechar ($this->mCheckImage, 5, $x, $y, $this->mCheckCode[$i], $bg_color);
   }
}
public function OutCheckImage()
{
   $this ->OutFileHeader();
   $this ->CreateCheckCode();
   $this ->CreateImage();
   $this ->SetDisturbColor();
   $this ->WriteCheckCodeToImage();
   imagepng($this->mCheckImage);
   imagedestroy($this->mCheckImage);
}
}
?>