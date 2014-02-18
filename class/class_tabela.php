<?
#########################################################################
#	Autor: Michał Klin  												#
#	Data: 03.08.2005        											#
#########################################################################
#	Opis:																#
#	Tabela rozgrywek													#
#########################################################################
class tabela {
	var $baza;
	var $tabela_terminarz;
	var $tabela_druzyny;
	var $tabela_terminy;
	
	var $tabela_punktow;
	var $liczba_druzyn;

	function tabela($baza, $tabela_ter, $tabela_dru, $tabela_term) {
		$this->baza = $baza;
		$this->tabela_terminarz = $tabela_ter;
		$this->tabela_druzyny = $tabela_dru;
		$this->tabela_terminy = $tabela_term;
		
		$dzisiaj=date("Y-m-d");
		//echo $dzisiaj."<br>";
		
		$result = $this->baza->select("*",$this->tabela_druzyny,"","order by dd1_nazwa");
		$size_result = $this->baza->size_result($result);
		$this->liczba_druzyn = $size_result;

		for($i=0;$i<$size_result;$i++){
			$row = $this->baza->row($result);
			
		//nazwy drużyn
			$this->tabela_punktow[$i][0] = $row[dd1_nazwa];	

			$result2 = $this->baza->select("*",$this->tabela_terminarz.", ".$this->tabela_terminy ,
			"mt_active=\"yes\" AND (mt_druzyna1=$row[dd1_id] OR mt_druzyna2=$row[dd1_id]) 
			AND mt_termin=dter_id ","");	// and dter_data<=\"$dzisiaj\"		
			$size_result2 = $this->baza->size_result($result2);
			
		//ilość rozegranych meczów
			$this->tabela_punktow[$i][1] = $size_result2;
			
			$zwyciestwa_d = 0;
			$porazki_d = 0;
			$remisy_d = 0;
			$stracone_d = 0;
			$strzelone_d = 0;
			$punkty_d = 0;
			
			$result2 = $this->baza->select("*",$this->tabela_terminarz.", ".$this->tabela_terminy ,
			"mt_active=\"yes\" AND mt_druzyna1=$row[dd1_id] 
			AND mt_termin=dter_id ","");// and dter_data<=\"$dzisiaj\"
			
			$size_result2 = $this->baza->size_result($result2);
			for ($j=0;$j<$size_result2;$j++){
				$row2 = $this->baza->row($result2);
				//zwycięstwo
				if ($row2[mt_wynik1]>$row2[mt_wynik2]){
					$zwyciestwa_d++;
				}
				//remis
				elseif ($row2[mt_wynik1]==$row2[mt_wynik2]){
					$remisy_d++;
				}
				//porażka
				elseif($row2[mt_wynik1]<$row2[mt_wynik2]){
					$porazki_d++;
				}
				$strzelone_d += $row2[mt_wynik1];
				$stracone_d += $row2[mt_wynik2];
			}
			$punkty_d = $zwyciestwa_d*3 + $remisy_d;
		
		//zwyciectwa, remisy, porażki DOM
			$this->tabela_punktow[$i][7] = $zwyciestwa_d;
			$this->tabela_punktow[$i][8] = $remisy_d;
			$this->tabela_punktow[$i][9] = $porazki_d;
			
		//bilans DOM	
			$this->tabela_punktow[$i][10] = $strzelone_d."-".$stracone_d;
		
			$zwyciestwa_w = 0;
			$porazki_w = 0;
			$remisy_w = 0;
			$stracone_w = 0;
			$strzelone_w = 0;
			$punkty_w = 0;
			
			$result2 = $this->baza->select("*",$this->tabela_terminarz.", ".$this->tabela_terminy ,
			"mt_active=\"yes\" AND mt_druzyna2=$row[dd1_id] 
			AND mt_termin=dter_id ","");// and dter_data<=\"$dzisiaj\"
			
			$size_result2 = $this->baza->size_result($result2);
			for ($j=0;$j<$size_result2;$j++){
				$row2 = $this->baza->row($result2);
				//zwycięstwo
				if ($row2[mt_wynik1]<$row2[mt_wynik2]){
					$zwyciestwa_w++;
				}
				//remis
				elseif ($row2[mt_wynik1]==$row2[mt_wynik2]){
					$remisy_w++;
				}
				//porażka
				elseif ($row2[mt_wynik1]>$row2[mt_wynik2]){
					$porazki_w++;
				}
				$strzelone_w += $row2[mt_wynik2];
				$stracone_w += $row2[mt_wynik1];
			}
			$punkty_w = $zwyciestwa_w*3 + $remisy_w;
		
		//zwyciectwa, remisy, porażki wyjazd
			$this->tabela_punktow[$i][11] = $zwyciestwa_w;
			$this->tabela_punktow[$i][12] = $remisy_w;
			$this->tabela_punktow[$i][13] = $porazki_w;
			
		//bilans wyjazd	
			$this->tabela_punktow[$i][14] = $strzelone_w."-".$stracone_w;

		//punkty DOM + Wyjazd	
			$this->tabela_punktow[$i][2] = $punkty_w+$punkty_d;

		//zwyciectwa, remisy, porażki Razem
			$this->tabela_punktow[$i][3] = $zwyciestwa_w+$zwyciestwa_d;
			$this->tabela_punktow[$i][4] = $remisy_w+$remisy_d;
			$this->tabela_punktow[$i][5] = $porazki_w+$porazki_d;
			
		//bilans Razem
			$strzelone = $strzelone_w+$strzelone_d;
			$stracone = $stracone_w+$stracone_d;
			$this->tabela_punktow[$i][6] = $strzelone."-".$stracone;			
		}	
		
		//kary punktowe
		for($i=0;$i<$size_result;$i++){	
/*			if ($this->tabela_punktow[$i][0]=="Oleśnica")
			$this->tabela_punktow[$i][2] += -1;
			if ($this->tabela_punktow[$i][0]=="Wisznia Mała")
			$this->tabela_punktow[$i][2] += -1;
*/		}		

		//wstępna segregacja
		for($i=0;$i<$size_result;$i++){	
			for ($j=0;$j<$size_result-$i;$j++){
				$k = $j + 1;
				$b=explode("-",$this->tabela_punktow[$j][6]);
				$bil_j = $b[0]-$b[1];
				$c=explode("-",$this->tabela_punktow[$k][6]);
				$bil_k = $c[0]-$c[1];
				if ($this->tabela_punktow[$j][2]<$this->tabela_punktow[$k][2] and $this->tabela_punktow[$k][0]!=""
				or ($this->tabela_punktow[$j][2]==$this->tabela_punktow[$k][2] and $bil_j<$bil_k and $this->tabela_punktow[$k][0]!="") 
				or ($this->tabela_punktow[$j][2]==$this->tabela_punktow[$k][2] and $bil_j==$bil_k and $b[0]<$c[0] and $this->tabela_punktow[$k][0]!="") ){
					$temp[0]=$this->tabela_punktow[$j][0];
					$temp[1]=$this->tabela_punktow[$j][1];
					$temp[2]=$this->tabela_punktow[$j][2];
					$temp[3]=$this->tabela_punktow[$j][3];
					$temp[4]=$this->tabela_punktow[$j][4];
					$temp[5]=$this->tabela_punktow[$j][5];
					$temp[6]=$this->tabela_punktow[$j][6];
					$temp[7]=$this->tabela_punktow[$j][7];
					$temp[8]=$this->tabela_punktow[$j][8];
					$temp[9]=$this->tabela_punktow[$j][9];
					$temp[10]=$this->tabela_punktow[$j][10];
					$temp[11]=$this->tabela_punktow[$j][11];
					$temp[12]=$this->tabela_punktow[$j][12];
					$temp[13]=$this->tabela_punktow[$j][13];
					$temp[14]=$this->tabela_punktow[$j][14];
					$this->tabela_punktow[$j][0]=$this->tabela_punktow[$k][0];
					$this->tabela_punktow[$j][1]=$this->tabela_punktow[$k][1];
					$this->tabela_punktow[$j][2]=$this->tabela_punktow[$k][2];
					$this->tabela_punktow[$j][3]=$this->tabela_punktow[$k][3];
					$this->tabela_punktow[$j][4]=$this->tabela_punktow[$k][4];
					$this->tabela_punktow[$j][5]=$this->tabela_punktow[$k][5];
					$this->tabela_punktow[$j][6]=$this->tabela_punktow[$k][6];
					$this->tabela_punktow[$j][7]=$this->tabela_punktow[$k][7];
					$this->tabela_punktow[$j][8]=$this->tabela_punktow[$k][8];
					$this->tabela_punktow[$j][9]=$this->tabela_punktow[$k][9];
					$this->tabela_punktow[$j][10]=$this->tabela_punktow[$k][10];
					$this->tabela_punktow[$j][11]=$this->tabela_punktow[$k][11];
					$this->tabela_punktow[$j][12]=$this->tabela_punktow[$k][12];
					$this->tabela_punktow[$j][13]=$this->tabela_punktow[$k][13];
					$this->tabela_punktow[$j][14]=$this->tabela_punktow[$k][14];
					$this->tabela_punktow[$k][0]=$temp[0];
					$this->tabela_punktow[$k][1]=$temp[1];
					$this->tabela_punktow[$k][2]=$temp[2];
					$this->tabela_punktow[$k][3]=$temp[3];
					$this->tabela_punktow[$k][4]=$temp[4];
					$this->tabela_punktow[$k][5]=$temp[5];
					$this->tabela_punktow[$k][6]=$temp[6];
					$this->tabela_punktow[$k][7]=$temp[7];
					$this->tabela_punktow[$k][8]=$temp[8];
					$this->tabela_punktow[$k][9]=$temp[9];
					$this->tabela_punktow[$k][10]=$temp[10];
					$this->tabela_punktow[$k][11]=$temp[11];
					$this->tabela_punktow[$k][12]=$temp[12];
					$this->tabela_punktow[$k][13]=$temp[13];
					$this->tabela_punktow[$k][14]=$temp[14];
				}
			}
		}
		
		//sprawdzanie czy taka sama ilość punktów
		for($i=0;$i<$size_result-1;$i++){	
			$k=$i+1;
			$poczatkowy=0;
			if ($this->tabela_punktow[$i][2]==$this->tabela_punktow[$k][2]){
				$poczatkowy = $i;
				$koncowy = $size_result-1;
				$i=$k;
				for ($j=$k;$j<$size_result-1;$j++){
					$l=$j+1;
					$i=$j;
					if ($this->tabela_punktow[$j][2]!=$this->tabela_punktow[$l][2]){
						$koncowy=$j;
						break;
					}
				}				
				//echo $poczatkowy."-".$koncowy."<br>";

				//spotkania bezpośrednie
				for ($m=$poczatkowy;$m<$koncowy;$m++){
					$result3 = $this->baza->select("*",$this->tabela_druzyny  ,
					"dd1_nazwa=\"".$this->tabela_punktow[$m][0]."\"","");
					$row3 = $this->baza->row($result3);
					$id_druzyny1 = $row3[dd1_id];

					for ($n=$poczatkowy+1;$n<=$koncowy;$n++){
						if ($m<$n){
							$result3 = $this->baza->select("*",$this->tabela_druzyny  ,
							"dd1_nazwa=\"".$this->tabela_punktow[$n][0]."\"","");
							$row3 = $this->baza->row($result3);
							$id_druzyny2 = $row3[dd1_id];

							$result4 = $this->baza->select("*",$this->tabela_terminarz.", ".$this->tabela_terminy,
							"mt_active=\"yes\" 
							AND mt_druzyna1=$id_druzyny1 AND mt_druzyna2=$id_druzyny2 
							AND mt_termin=dter_id","");// and dter_data<=\"$dzisiaj\"
			
							$size_result4 = $this->baza->size_result($result4);					
							if ($size_result4>0){
								$row4 = $this->baza->row($result4);
								$this->tabela_punktow[$m][17] += 0;
								$this->tabela_punktow[$m][18] += 0;
								$this->tabela_punktow[$m][19] += 0;
								$this->tabela_punktow[$n][17] += 0;
								$this->tabela_punktow[$n][18] += 0;
								$this->tabela_punktow[$n][19] += 0;
							
								//liczba spotkań bezpośrednich
								$this->tabela_punktow[$m][15] += $size_result4;
								$this->tabela_punktow[$n][15] += $size_result4;	
								
								if ($row4[mt_wynik1]>$row4[mt_wynik2]){
									$this->tabela_punktow[$m][17] += 1;
									$this->tabela_punktow[$n][19] += 1;
								}					
								if ($row4[mt_wynik1]==$row4[mt_wynik2]){
									$this->tabela_punktow[$m][18] += 1;
									$this->tabela_punktow[$n][18] += 1;
								}					
								if ($row4[mt_wynik1]<$row4[mt_wynik2]){
									$this->tabela_punktow[$m][19] += 1;
									$this->tabela_punktow[$n][17] += 1;
								}		
											
								//punkty
								$this->tabela_punktow[$m][16] = $this->tabela_punktow[$m][17]*3+$this->tabela_punktow[$m][18];
								$this->tabela_punktow[$n][16] = $this->tabela_punktow[$n][17]*3+$this->tabela_punktow[$n][18];
								
								//bilans wstęp
								$this->tabela_punktow[$m][21] += $row4[mt_wynik2];
								$this->tabela_punktow[$n][21] += $row4[mt_wynik1];
								$this->tabela_punktow[$m][22] += $row4[mt_wynik1];
								$this->tabela_punktow[$n][22] += $row4[mt_wynik2];//1221
								
								//bilans final
								$this->tabela_punktow[$m][20] = $this->tabela_punktow[$m][22]."-".$this->tabela_punktow[$m][21];
								$this->tabela_punktow[$n][20] = $this->tabela_punktow[$n][22]."-".$this->tabela_punktow[$n][21];
							}
							
							$result4 = $this->baza->select("*",$this->tabela_terminarz.", ".$this->tabela_terminy,
							"mt_active=\"yes\" 
							AND mt_druzyna2=$id_druzyny1 AND mt_druzyna1=$id_druzyny2 
							AND mt_termin=dter_id","");// and dter_data<=\"$dzisiaj\"
			
							$size_result4 = $this->baza->size_result($result4);					
							if ($size_result4>0){
								$row4 = $this->baza->row($result4);
								$this->tabela_punktow[$m][17] += 0;
								$this->tabela_punktow[$m][18] += 0;
								$this->tabela_punktow[$m][19] += 0;
								$this->tabela_punktow[$n][17] += 0;
								$this->tabela_punktow[$n][18] += 0;
								$this->tabela_punktow[$n][19] += 0;
							
								//liczba spotkań bezpośrednich
								$this->tabela_punktow[$m][15] += $size_result4;
								$this->tabela_punktow[$n][15] += $size_result4;	
								
								if ($row4[mt_wynik1]<$row4[mt_wynik2]){
									$this->tabela_punktow[$m][17] += 1;
									$this->tabela_punktow[$n][19] += 1;
								}					
								if ($row4[mt_wynik1]==$row4[mt_wynik2]){
									$this->tabela_punktow[$m][18] += 1;
									$this->tabela_punktow[$n][18] += 1;
								}					
								if ($row4[mt_wynik1]>$row4[mt_wynik2]){
									$this->tabela_punktow[$m][19] += 1;
									$this->tabela_punktow[$n][17] += 1;
								}		
											
								//punkty
								$this->tabela_punktow[$m][16] = $this->tabela_punktow[$m][17]*3+$this->tabela_punktow[$m][18];
								$this->tabela_punktow[$n][16] = $this->tabela_punktow[$n][17]*3+$this->tabela_punktow[$n][18];
								
								//bilans wstęp
								$this->tabela_punktow[$m][21] += $row4[mt_wynik1];
								$this->tabela_punktow[$n][21] += $row4[mt_wynik2];
								$this->tabela_punktow[$m][22] += $row4[mt_wynik2];
								$this->tabela_punktow[$n][22] += $row4[mt_wynik1];//1221
								
								//bilans final
								$this->tabela_punktow[$m][20] = $this->tabela_punktow[$m][22]."-".$this->tabela_punktow[$m][21];
								$this->tabela_punktow[$n][20] = $this->tabela_punktow[$n][22]."-".$this->tabela_punktow[$n][21];
							}

						}
					}
				}
			}
		}		
		
		//końcowa segregacja
		for($i=0;$i<$size_result;$i++){	
			for ($j=0;$j<$size_result-$i-1;$j++){
				$k = $j + 1;
				$bil_1 = explode("-",$this->tabela_punktow[$j][6]);
				$strzelone_1 = $bil_1[0];
				$stracone_1 = $bil_1[1];
				$bil_2 = explode("-",$this->tabela_punktow[$k][6]);
				$strzelone_2 = $bil_2[0];
				$stracone_2 = $bil_2[1];
				
				$bilans_1 = $strzelone_1 - $stracone_1;
				$bilans_2 = $strzelone_2 - $stracone_2;
				
				$bil_bezp1 = explode("-",$this->tabela_punktow[$j][20]);
				$bezp_bil_1 = $bil_bezp1[0]-$bil_bezp1[1];
				$bezp_bil_1_strzel = $bil_bezp1[0];
				$bil_bezp2 = explode("-",$this->tabela_punktow[$k][20]);
				$bezp_bil_2 = $bil_bezp2[0]-$bil_bezp2[1];
				$bezp_bil_2_strzel = $bil_bezp2[0];

				
				//zamień gdy:
				if (
					$this->tabela_punktow[$j][2]==$this->tabela_punktow[$k][2] 
					and (
						$this->tabela_punktow[$j][16]<$this->tabela_punktow[$k][16] 
						or (
							$this->tabela_punktow[$j][16]==$this->tabela_punktow[$k][16] 
							and (
								$bezp_bil_1<$bezp_bil_2
								or (
									$bezp_bil_1==$bezp_bil_2
									and(
										$bezp_bil_1_strzel<$bezp_bil_2_strzel
										or (
											$bezp_bil_1_strzel==$bezp_bil_2_strzel
											and (
												$bilans_1<$bilans_2 
												or (
													$bilans_1==$bilans_2 
													and 
													$strzelone_1<$strzelone_2
												)
											)
										)
									)
								)
							)
						)						
					)					
				)
				
				
				{
					$temp[0]=$this->tabela_punktow[$j][0];
					$temp[1]=$this->tabela_punktow[$j][1];
					$temp[2]=$this->tabela_punktow[$j][2];
					$temp[3]=$this->tabela_punktow[$j][3];
					$temp[4]=$this->tabela_punktow[$j][4];
					$temp[5]=$this->tabela_punktow[$j][5];
					$temp[6]=$this->tabela_punktow[$j][6];
					$temp[7]=$this->tabela_punktow[$j][7];
					$temp[8]=$this->tabela_punktow[$j][8];
					$temp[9]=$this->tabela_punktow[$j][9];
					$temp[10]=$this->tabela_punktow[$j][10];
					$temp[11]=$this->tabela_punktow[$j][11];
					$temp[12]=$this->tabela_punktow[$j][12];
					$temp[13]=$this->tabela_punktow[$j][13];
					$temp[14]=$this->tabela_punktow[$j][14];
					$temp[15]=$this->tabela_punktow[$j][15];
					$temp[16]=$this->tabela_punktow[$j][16];
					$temp[17]=$this->tabela_punktow[$j][17];
					$temp[18]=$this->tabela_punktow[$j][18];
					$temp[19]=$this->tabela_punktow[$j][19];
					$temp[20]=$this->tabela_punktow[$j][20];
					$this->tabela_punktow[$j][0]=$this->tabela_punktow[$k][0];
					$this->tabela_punktow[$j][1]=$this->tabela_punktow[$k][1];
					$this->tabela_punktow[$j][2]=$this->tabela_punktow[$k][2];
					$this->tabela_punktow[$j][3]=$this->tabela_punktow[$k][3];
					$this->tabela_punktow[$j][4]=$this->tabela_punktow[$k][4];
					$this->tabela_punktow[$j][5]=$this->tabela_punktow[$k][5];
					$this->tabela_punktow[$j][6]=$this->tabela_punktow[$k][6];
					$this->tabela_punktow[$j][7]=$this->tabela_punktow[$k][7];
					$this->tabela_punktow[$j][8]=$this->tabela_punktow[$k][8];
					$this->tabela_punktow[$j][9]=$this->tabela_punktow[$k][9];
					$this->tabela_punktow[$j][10]=$this->tabela_punktow[$k][10];
					$this->tabela_punktow[$j][11]=$this->tabela_punktow[$k][11];
					$this->tabela_punktow[$j][12]=$this->tabela_punktow[$k][12];
					$this->tabela_punktow[$j][13]=$this->tabela_punktow[$k][13];
					$this->tabela_punktow[$j][14]=$this->tabela_punktow[$k][14];
					$this->tabela_punktow[$j][15]=$this->tabela_punktow[$k][15];
					$this->tabela_punktow[$j][16]=$this->tabela_punktow[$k][16];
					$this->tabela_punktow[$j][17]=$this->tabela_punktow[$k][17];
					$this->tabela_punktow[$j][18]=$this->tabela_punktow[$k][18];
					$this->tabela_punktow[$j][19]=$this->tabela_punktow[$k][19];
					$this->tabela_punktow[$j][20]=$this->tabela_punktow[$k][20];
					$this->tabela_punktow[$k][0]=$temp[0];
					$this->tabela_punktow[$k][1]=$temp[1];
					$this->tabela_punktow[$k][2]=$temp[2];
					$this->tabela_punktow[$k][3]=$temp[3];
					$this->tabela_punktow[$k][4]=$temp[4];
					$this->tabela_punktow[$k][5]=$temp[5];
					$this->tabela_punktow[$k][6]=$temp[6];
					$this->tabela_punktow[$k][7]=$temp[7];
					$this->tabela_punktow[$k][8]=$temp[8];
					$this->tabela_punktow[$k][9]=$temp[9];
					$this->tabela_punktow[$k][10]=$temp[10];
					$this->tabela_punktow[$k][11]=$temp[11];
					$this->tabela_punktow[$k][12]=$temp[12];
					$this->tabela_punktow[$k][13]=$temp[13];
					$this->tabela_punktow[$k][14]=$temp[14];
					$this->tabela_punktow[$k][15]=$temp[15];
					$this->tabela_punktow[$k][16]=$temp[16];
					$this->tabela_punktow[$k][17]=$temp[17];
					$this->tabela_punktow[$k][18]=$temp[18];
					$this->tabela_punktow[$k][19]=$temp[19];
					$this->tabela_punktow[$k][20]=$temp[20];
				}
			}
		}
		/**/
	}
	
	function get_tabela(){
		return $this->tabela_punktow;
	}		
	function get_liczba_dryzyn(){
		return $this->liczba_druzyn;
	}
	
}
?>