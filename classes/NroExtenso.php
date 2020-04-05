<?php

namespace nroextenso;

/*
 * Autor 				: Oséias Slompo
 * Descrição			: Converte número em número por extenso
 * Última Atualização	: 2020-04-04
 * Versão				: 1.0
 * $valor 				: O número precisa ser formatado como #0,00, #.##0,00, #0.00 ou #,##0.00"
 * $moeda 				: Somente R$ ou U$
 * Limitações			: Valor máximo convertido de 999.999.999.999,99
 * Licença				: MIT
 */

class NroExtenso {
	private $valor = 0;
	private $moeda = null;

	public function getValor(){
		return $this->valor;
	}

	public function setValor($valor){
		if (strpos(strrev($valor),",") != 2 && strpos(strrev($valor),".") != 2){
			throw new \Exception("O valor $valor não corresponde ao formato #0,00, #.##0,00, #0.00 ou #,##0.00", 1);
		}
		$formVlr = preg_replace("/[,.]/", "", $valor);
		$formVlr = substr_replace(strrev($formVlr),".",2,0);
		$this->valor = strrev($formVlr);
		return $this;
	}

	public function getMoeda(){
		return $this->moeda;
	}

	public function setMoeda($moeda){
		if ($moeda != "R$" && $moeda != "U$"){
			throw new \Exception("Moeda precisa ser R$ ou U$.", 2);
		}
		$this->moeda = $moeda;
		return $this;
	}

	public function convNro(NroExtenso $p) {
		$extenso="";
		$ArrStr = Array("","um","dois","três","quatro","cinco","seis","sete","oito","nove","dez","onze","doze","treze","quatorze","quinze","dezesseis","dezessete","dezoito","dezenove","20"=>"vinte","30"=>"trinta","40"=>"quarenta","50"=>"cinquenta","60"=>"sessenta","70"=>"setenta","80"=>"oitenta","90"=>"noventa","100"=>"cem","200"=>"duzentos","300"=>"trezentos","400"=>"quatrocentos","500"=>"quinhentos","600"=>"seiscentos","700"=>"setecentos","800"=>"oitocentos","900"=>"novecentos");
		$vlrExt = $p->getValor();
		$moeExt = $p->getMoeda();
		$vlrExt = str_pad($vlrExt , 15 , '0' , STR_PAD_LEFT);
		$extenso = "(";
		for($i = 0; $i < 10; $i += 3) {
			$cen = intval($vlrExt[$i]) * 100;
			$dez = intval($vlrExt[$i + 1]) * 10;
			$uni = intval($vlrExt[$i + 2]);
			if($cen + $dez + $uni != 0) {
				if($cen == 100 && $dez < 20 && $uni != 0) {
					$extenso .= ("cento" . " e " . $ArrStr[$dez + $uni]);
				}else if ($cen == 100 && $dez != 0 && $uni != 0) {
					$extenso .= ("cento" . " e " . $ArrStr[$dez] . " e " . $ArrStr[$uni]);
				}else if ($cen == 100 && $dez != 0 && $uni == 0) {
					$extenso .= ("cento" . " e " . $ArrStr[$dez]);
				}else if ($cen == 100 && $dez == 0 && $uni != 0) {
					$extenso .= ("cento" . " e " . $ArrStr[$uni]);
				}else if ($cen != 0 && $dez < 20 && $uni != 0) {
					$extenso .= ($ArrStr[$cen] . " e " . $ArrStr[$dez + $uni]);
				}else if ($cen != 0 && $dez != 0 && $uni != 0) {
					$extenso .= ($ArrStr[$cen] . " e " . $ArrStr[$dez] . " e " . $ArrStr[$uni]);
				}else if ($cen != 0 && $dez != 0 && $uni == 0) {
					$extenso .= ($ArrStr[$cen] . " e " . $ArrStr[$dez]);
				}else if ($cen != 0 && $dez == 0 && $uni == 0) {
					$extenso .= ($ArrStr[$cen]);
				}else if ($cen != 0 && $dez == 0 && $uni != 0) {
					$extenso .= ($ArrStr[$cen] . " e " . $ArrStr[$uni]);
				}else if ($cen == 0 && $dez == 0 && $uni != 0) {
					$extenso .= ($ArrStr[$uni]);
				}else if ($cen == 0 && $dez < 20) {
					$extenso .= ($ArrStr[$dez + $uni]);
				}else if ($cen == 0 && $dez >= 20 && $uni != 0) {
					$extenso .= ($ArrStr[$dez] . " e " . $ArrStr[$uni]);
				}else if ($cen == 0 && $dez >= 20 && $uni == 0) {
					$extenso .= ($ArrStr[$dez]);
				}
				if($cen + $dez + $uni > 1 && $i == 0 && intval(substr($vlrExt, $i + 3, 9)) != 0) {
					$extenso .= " bilhões";
				} else if ($cen + $dez + $uni > 1 && $i == 0 && intval(substr($vlrExt, $i + 3, 9)) == 0) {
					$extenso .= " bilhões de";
				}else if ($cen + $dez + $uni == 1 && $i == 0 && intval(substr($vlrExt, $i + 3, 9)) != 0) {
					$extenso .= " bilhão";
				}else if ($cen + $dez + $uni == 1 && $i == 0 && intval(substr($vlrExt, $i + 3, 9)) == 0) {
					$extenso .= " bilhão de";
				}else if ($cen + $dez + $uni > 1 && $i == 3 && intval(substr($vlrExt, $i + 3, 6)) != 0) {
					$extenso .= " milhões";
				}else if ($cen + $dez + $uni > 1 && $i == 3 && intval(substr($vlrExt, $i + 3, 6)) == 0) {
					$extenso .= " milhões de";
				}else if ($cen + $dez + $uni == 1 && $i == 3 && intval(substr($vlrExt, $i + 3, 6)) != 0) {
					$extenso .= " milhão";
				}else if ($cen + $dez + $uni == 1 && $i == 3 && intval(substr($vlrExt, $i + 3, 6)) == 0) {
					$extenso .= " milhão de";
				}else if ($cen + $dez + $uni >= 1 && $i == 6) {
					$extenso .= " mil";
				}
				if ($i <= 6){
					if (substr($vlrExt, $i, 3) != "000" && substr($vlrExt, $i + 3, 1) == "0" && substr($vlrExt, $i + 4, 2) != "00"){
						$extenso .= " e ";
					}else if (substr($vlrExt, $i, 3) != "000" && substr($vlrExt, $i + 3, 1) != "0" && substr($vlrExt, $i + 4, 2) == "00"){
						$extenso .= " e ";
					}else if (substr($vlrExt, $i, 3) != "000" && substr($vlrExt, $i + 3, 3) != "000"){
						$extenso .= ", ";
					}else if (!strpos($extenso,"de")){
						$extenso .= " e ";
					}
				}
			}
		}
		if(intval(substr($vlrExt, 0, 12)) == 1 && $moeExt == "R$") {
			$extenso .= " real";
		}else if (intval(substr($vlrExt, 0,12)) > 1 && $moeExt == "R$") {
			$extenso .= " reais";
		}else if (intval(substr($vlrExt, 0, 12)) == 1 && $moeExt == "U$") {
			$extenso .= " dólar";
		}else if (intval(substr($vlrExt, 0,12)) > 1 && $moeExt == "U$") {
			$extenso .= " dólares";
		}
		$dcent = intval($vlrExt[13]) * 10;
		$ucent = intval($vlrExt[14]);
		if($dcent + $ucent != 0) {
			if($dcent ==0 && $ucent == 1 && intval(substr($vlrExt, 0, 12) != 0)) {
				$extenso .= " e " . $ArrStr[$ucent]  . " centavo";
			}else if ($dcent == 0 && $ucent == 1 && intval(substr($vlrExt, 0, 12) == 0)) {
				$extenso .= $ArrStr[$ucent]  . " centavo";
			}else if ($dcent + $ucent > 1 && $dcent + $ucent <= 20 && intval(substr($vlrExt, 0,12) != 0)) {
				$extenso .= " e " . $ArrStr[$dcent + $ucent]  . " centavos";
			}else if ($dcent + $ucent > 1 && $dcent + $ucent <= 20 && intval(substr($vlrExt, 0,12) == 0)) {
					$extenso .= $ArrStr[$dcent + $ucent]  . " centavos";
			}else if ($dcent >= 2 && $ucent != 0 && intval(substr($vlrExt, 0,12) != 0)) {
				$extenso .= " e " . $ArrStr[$dcent] . " e " . $ArrStr[$ucent]  . " centavos";
			}else if ($dcent >= 2 && $ucent != 0 && intval(substr($vlrExt, 0,12) == 0)) {
				$extenso .= $ArrStr[$dcent] . " e " . $ArrStr[$ucent]  . " centavos";
			}else if (($dcent >= 2 && $ucent == 0)) {
				$extenso .= " e " . $ArrStr[$dcent]  . " centavos";
			}
		}
		$extenso .= ")";
		return mb_strtoupper($extenso);
	}
}
?>