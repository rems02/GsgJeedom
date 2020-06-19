<?php
#--
# Copyright (c) 2016 R.Syrek aka Rem'S http://Domotique-Home.fr
#
# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
#++

#########
#
#	----- Utilisation -----
#
# 	--> Afficher les données par année.  
# - Ajouter une commande script de TYPE "info"
# - Sélectionner le scripr gsg.php ()
# - Renseigner les variable
# 	ip=Adresse_de_GSG (/!\ obligatoire) 
#	an=Année (par défaut année en cour)
#  	EXEMPLE: /var/www/html/core/php/../../plugins/script/core/ressources/gsg.php ip=192.168.0.1 an=2015
#  
#  
#   --> Afficher le bouton de consommation
# - Ajouter une commande script de TYPE "action"
# - Sélectionner le script gsg.php (/var/www/html/core/php/../../plugins/script/core/ressources/gsg.php)
# - Renseigner les variable
# 	ip=Adresse_de_GSG (/!\ obligatoire) 
#	conso=On  (/!\ obligatoire) 
#	EXEMPLE: /var/www/html/core/php/../../plugins/script/core/ressources/gsg.php ip=192.168.0.1 conso=On
#
##########
  
#protection contre l'execution direct
if (php_sapi_name() != 'cli' || isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['argc'])) {
    header("Status: 404 Not Found");
    header('HTTP/1.0 404 Not Found');
    $_SERVER['REDIRECT_STATUS'] = 404;
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}
# parsing des arguments
if (isset($argv)) {
    foreach ($argv as $arg) {
        $argList = explode('=', $arg);
        if (isset($argList[0]) && isset($argList[1])) {
            $_GET[$argList[0]] = $argList[1];
        }
    }
}

//Recuperation et verification des variables
if(!isset($_GET['ip'])) {echo '</br></br>IP est Oblogatoire !'; exit; } else {$ip = $_GET['ip'];}
if(!isset($_GET['an'])) {$an = date('Y');} else {$an = $_GET['an'];}
if(!isset($_GET['conso'])) {$conso =''; } else {$conso = $_GET['conso'];}

if($conso == "On")
{
file_get_contents("http://$ip/gsg2/data_granulee.php?value=1");
}
echo $conso;

//recuperation json de module
$an2 = $an+1;
$json_source = file_get_contents("http://$ip/gsg2/json.php?json=1&an=$an2");
//traitement de json
$json_data = json_decode($json_source);
//exploitation des variables json
$StockIni = $json_data->StockIni;
$Reliquat = $json_data->Reliquat;
$PrixSac = $json_data->PrixSac;
$NbrSacConso = $json_data->NbrSacConso;
$Septembre = "Septembre $an";
$Septembre = $json_data->$Septembre;
$Octobre = 'Octobre ' . $an;
$Octobre = $json_data->$Octobre;
$Novembre = 'Novembre ' . $an;
$Novembre = $json_data->$Novembre;
$Decembre = 'Decembre ' . $an;
$Decembre = $json_data->$Decembre;
$Janvier = 'Janvier ' . $an2;
$Janvier = $json_data->$Janvier;
$Fevrier = 'Fevrier ' . $an2;
$Fevrier = $json_data->$Fevrier;
$Mars = 'Mars ' . $an2;
$Mars = $json_data->$Mars;
$Avril = 'Avril ' . $an2;
$Avril = $json_data->$Avril;
$Mai = 'Mai ' . $an2;
$Mai = $json_data->$Mai;
$Juin = 'Juin ' . $an2;
$Juin = $json_data->$Juin;
$Juillet = 'Jullet ' . $an2;
$Juillet = $json_data->$Juillet;
$Aout = 'Aout ' . $an2;
$Aout = $json_data->$Aout;

//Calcule Stock Total
$st = $StockIni + $Reliquat;
//Calcule Cout Stock
$cs = $st * $PrixSac;
//Calcule Nombre de sacs restant
$sr = $st - $NbrSacConso;

//Affichage données
echo '</br>Période: ' . $an . '/' . $an2;
if($st > 1){echo '</br>Stock Total: ' .$st. ' sacs';} elseif($st == 1) {echo '</br>Stock Total: ' .$st. ' sac';} else{echo '</br>Stock Total: ' .$st;}
echo '</br>Sacs Consommés: ' .$NbrSacConso;
echo '</br>Sacs restants: ' .$sr;
echo '</br>Cout Stock: ' .$cs. ' &euro;';
echo '</br></br>Consomation Mensuelle</br>';
if($Septembre > 1){echo 'Septembre: ' .$Septembre. ' sacs';} elseif($Septembre == 1) {echo 'Septembre: ' .$Septembre. ' sac';} else{echo 'Septembre: ' .$Septembre;}
echo '</br>';
if($Octobre > 1){echo 'Octobre: ' .$Octobre. ' sacs';} elseif($Octobre == 1) {echo 'Octobre: ' .$Octobre. ' sac';} else{echo 'Octobre: ' .$Octobre;}
echo '</br>';
if($Novembre > 1){echo 'Novembre: ' .$Novembre. ' sacs';} elseif($Novembre == 1) {echo 'Novembre: ' .$Novembre. ' sac';} else{echo 'Novembre: ' .$Novembre;}
echo '</br>';
if($Decembre > 1){echo 'Decembre: ' .$Decembre. ' sacs';} elseif($Decembre == 1) {echo 'Decembre: ' .$Decembre. ' sac';} else{echo 'Decembre: ' .$Decembre;}
echo '</br>';
if($Janvier > 1){echo 'Janvier: ' .$Janvier. ' sacs';} elseif($Janvier == 1) {echo 'Janvier: ' .$Janvier. ' sac';} else{echo 'Janvier: ' .$Janvier;}
echo '</br>';
if($Fevrier > 1){echo 'Fevrier: ' .$Fevrier. ' sacs';} elseif($Fevrier == 1) {echo 'Fevrier: ' .$Fevrier. ' sac';} else{echo 'Fevrier: ' .$Fevrier;}
echo '</br>';
if($Mars > 1){echo 'Mars: ' .$Mars. ' sacs';} elseif($Mars == 1) {echo 'Mars: ' .$Mars. ' sac';} else{echo 'Mars: ' .$Mars;}
echo '</br>';
if($Avril > 1){echo 'Avril: ' .$Avril. ' sacs';} elseif($Avril == 1) {echo 'Avril: ' .$Avril. ' sac';} else{echo 'Avril: ' .$Avril;}
echo '</br>';
if($Mai > 1){echo 'Mai: ' .$Mai. ' sacs';} elseif($Mai == 1) {echo 'Mai: ' .$Mai. ' sac';} else{echo 'Mai: ' .$Mai;}
echo '</br>';
if($Juin > 1){echo 'Juin: ' .$Juin. ' sacs';} elseif($Juin == 1) {echo 'Juin: ' .$Juin. ' sac';} else{echo 'Juin: ' .$Juin;}
echo '</br>';
if($Juillet > 1){echo 'Juillet: ' .$Juillet. ' sacs';} elseif($Juillet == 1) {echo 'Juillet: ' .$Juillet. ' sac';} else{echo 'Juillet: ' .$Juillet;}
echo '</br>';
if($Aout > 1){echo 'Aout: ' .$Aout. ' sacs';} elseif($Aout == 1) {echo 'Aout: ' .$Aout. ' sac';} else{echo 'Aout: ' .$Aout;}
echo '</br>';


?>