<?php

/*
 *
 *  ____			_		_   __  __ _				  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___	  |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|	 |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);


namespace pocketmine\utils;


class Color{

	const COLOR_DYE_BLACK = 0;//dye colors
	const COLOR_DYE_RED = 1;
	const COLOR_DYE_GREEN = 2;
	const COLOR_DYE_BROWN = 3;
	const COLOR_DYE_BLUE = 4;
	const COLOR_DYE_PURPLE = 5;
	const COLOR_DYE_CYAN = 6;
	const COLOR_DYE_LIGHT_GRAY = 7;
	const COLOR_DYE_GRAY = 8;
	const COLOR_DYE_PINK = 9;
	const COLOR_DYE_LIME = 10;
	const COLOR_DYE_YELLOW = 11;
	const COLOR_DYE_LIGHT_BLUE = 12;
	const COLOR_DYE_MAGENTA = 13;
	const COLOR_DYE_ORANGE = 14;
	const COLOR_DYE_WHITE = 15;

	private $red = 0;
	private $green = 0;
	private $blue = 0;

	/** @var \SplFixedArray */
	public static $dyeColors = null;

	/** @var int */
	protected $a, $r, $g, $b;
	
	public function __construct(int $r, int $g, int $b, int $a = 0xff){
		$this->r = $r & 0xff;
		$this->g = $g & 0xff;
		$this->b = $b & 0xff;
		$this->a = $a & 0xff;
	}

	/**
	 * Returns the alpha (transparency) value of this colour.
	 * @return int
	 */
	public function getA() : int{
		return $this->a;
	}

	/**
	 * Sets the alpha (opacity) value of this colour, lower = more transparent
	 * @param int $a
	 */
	public function setA(int $a){
		$this->a = $a & 0xff;
	}

	/**
	 * Retuns the red value of this colour.
	 * @return int
	 */
	public function getR() : int{
		return $this->r;
	}

	/**
	 * Sets the red value of this colour.
	 * @param int $r
	 */
	public function setR(int $r){
		$this->r = $r & 0xff;
	}

	/**
	 * Returns the green value of this colour.
	 * @return int
	 */
	public function getG() : int{
		return $this->g;
	}

	/**
	 * Sets the green value of this colour.
	 * @param int $g
	 */
	public function setG(int $g){
		$this->g = $g & 0xff;
	}

	/**
	 * Returns the blue value of this colour.
	 * @return int
	 */
	public function getB() : int{
		return $this->b;
	}

	/**
	 * Sets the blue value of this colour.
	 * @param int $b
	 */
	public function setB(int $b){
		$this->b = $b & 0xff;
	}

	/**
	 * Returns a Color from the supplied RGB colour code (24-bit)
	 * @param int $code
	 *
	 * @return Color
	 */
	public static function fromRGB(int $code){
		return new Color(($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff);
	}

	/**
	 * Returns a Color from the supplied ARGB colour code (32-bit)
	 *
	 * @param int $code
	 *
	 * @return Color
	 */
	public static function fromARGB(int $code){
		return new Color(($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff, ($code >> 24) & 0xff);
	}

	/**
	 * Returns an ARGB 32-bit colour value.
	 * @return int
	 */
	public function toARGB() : int{
		return ($this->a << 24) | ($this->r << 16) | ($this->g << 8) | $this->b;
	}

	/**
	 * Returns a little-endian ARGB 32-bit colour value.
	 * @return int
	 */
	public function toBGRA() : int{
		return ($this->b << 24) | ($this->g << 16) | ($this->r << 8) | $this->a;
	}

	/**
	 * Returns an RGBA 32-bit colour value.
	 * @return int
	 */
	public function toRGBA() : int{
		return ($this->r << 24) | ($this->g << 16) | ($this->b << 8) | $this->a;
	}

	/**
	 * Returns a little-endian RGBA colour value.
	 * @return int
	 */
	public function toABGR() : int{
		return ($this->a << 24) | ($this->b << 16) | ($this->g << 8) | $this->r;
	}

	public static function fromABGR(int $code){
		return new Color($code & 0xff, ($code >> 8) & 0xff, ($code >> 16) & 0xff, ($code >> 24) & 0xff);
	}


	public static function getRGB($r, $g, $b) {
		return new Color((int)$r, (int)$g, (int)$b);
	}

	public static function averageColor(Color ...$colors) {
		$tr = 0;//total red
		$tg = 0;//green
		$tb = 0;//blue
		$count = 0;
		foreach ($colors as $c) {
			$tr += $c->getRed();
			$tg += $c->getGreen();
			$tb += $c->getBlue();
			++$count;
		}
		return Color::getRGB($tr / $count, $tg / $count, $tb / $count);
	}

	public static function getDyeColor($id) {
		if (isset(self::$dyeColors[$id])) {
			return clone self::$dyeColors[$id];
		}
		return Color::getRGB(0, 0, 0);
	}

	public function getRed() {
		return (int)$this->red;
	}

	public function getBlue() {
		return (int)$this->blue;
	}

	public function getGreen() {
		return (int)$this->green;
	}

	public function getColorCode() {
		return ($this->red << 16 | $this->green << 8 | $this->blue) & 0xffffff;
	}

	public function __toString() {
		return "Color(red:" . $this->red . ", green:" . $this->green . ", blue:" . $this->blue . ")";
	}
}