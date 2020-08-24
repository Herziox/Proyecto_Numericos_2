<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Yellowtail&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="./css/13_Thoma s_Guass-Jacobi.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="https://i.imgur.com/c1VjDtt.png" />
    <link href="https://fonts.googleapis.com/css2?family=Bellota:wght@400;700&display=swap" rel="stylesheet" />
   <!-- <link rel="stylesheet" href="../css/main.css"> -->
   <link rel="stylesheet" href="./css/estilos.css">
   <script src="https://www.geogebra.org/apps/deployggb.js"></script>
    <title>Proyecto de Segundp Bimestre</title>
<script src="./js/codigos.js"> </script>
</head>
    
<body>

    <header class="">
    
    </header>

    <nav class="navbar navbar-dark bg-dark navbar-expand-lg sticky-top">
        
        <a class="navbar-brand btn-outline-success" href="#"> <img src="https://i.imgur.com/c1VjDtt.png" class="img-fluid image-nav"  alt="Responsive image"> Proyecto de Segundp Bimestre </a>
            
        <a class="navbar-brand btn-outline-success" href="#ingresarDatos">Ingresar Datos</a>
            <button class="navbar-toggler" type="button" >
                <span ></span>
            </button>
        <a class="navbar-brand btn-outline-success" href="#resultados">Resultados</a>
        <button class="navbar-toggler" type="button" >
            <span ></span>
        </button>
            
        </nav>

    <div class="title">
            <h1> Proyecto de Segundp Bimestre (Version 0.1)</h1>
     </div>
    <section id ="ingresarDatos"class="datos-details">
       
        <div class="form-all">
            <h3 class="centrar-texto texto-blancho">Ingreso de datos</h3>
            <form class="formulario" action="" method="post" enctype="multipart/form-data">
            
            <div class="">
                <label for="validationDefault02"><input type="radio" name="intro" value="texto" onchange="invisible()" checked> Ingresar puntos</label>
                <input  type="text" class="form-control" id="validationDefault01" name="puntos" placeholder="2,-6;3,-1;4,6" value="<?php if (isset($_POST['puntos']))echo $_POST['puntos']; else echo "2,-6;3,-1;4,6"; ?>">
            </div>
            <label for="validationDefault02"><input type="radio" name="intro" value="archivo" onchange="visible()" > Cargar archivo</label>
                <br>
            <div class="input-group">          
                <input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" name="valores">
                <label class="custom-file-label" for="inputGroupFile04"><?php if (isset($_POST['valores']))echo $_POST['valores']; else echo "Seleccionar Archivo"; ?></label>        
            </div>
            <div class="flexbox">
            <input class="btn btn-primary"  class="centrar boton" type="submit" value="Calcular" id="btnA" name="btnA" />
            </div> 
            </form>
        </div>
        
    </section>

<section class="results" id="resultados">
<?php
//Main
if(isset($_POST['btnA'])){
    $yMax=-100;
    if($_POST['intro']=="texto"){
        $puntos=$_POST['puntos'];
        $valores = explode(";",$puntos);
        $n=count($valores);
        for ($i=0; $i < $n; $i++) { 
            $value=explode(",",$valores[$i]);
            $x[$i]=$value[0];
            $y[$i]=$value[1];
            if($y[$i]>$yMax)
                $yMax=$y[$i];
        }
    }else{
        copy($_FILES['valores']['tmp_name'],$_FILES['valores']['name']);
        $puntos=$_FILES['valores']['name'];
        $leer = file($puntos);
        $campo='';
        foreach ($leer as $linea){
           $campo = $campo.$linea.';';
        }
       
        $valores = explode(";",$campo);
        $n =count($valores)-1; 
        for ($i=0; $i < $n; $i++) { 
           
            $value=explode(",",$valores[$i]);
            $x[$i]=$value[0];
            if($i<$n-1){
                $y[$i]=substr($value[1],0,-2);
            }else{
                $y[$i]=$value[1];
            }
            if($y[$i]>$yMax)
                $yMax=$y[$i];
           
        }
    }
    echo "<h2>Puntos ingresados</h2>";
    $values[0]=$x;
    $values[1]=$y;
    printMatrix($values,null);

    //Funcion Principal
    $funcion = '(1/(1+a*(x-x0)**2))+(c/(1+a*(x-x0-b))**2)';
    echo "Función: $funcion";
    echo "<br>";
    $funcion = str_replace('x0','4000',$funcion);
    echo "Función: $funcion";
    echo "<br>";
    $funcion = armarFuncionS($x,$y,$funcion,$n,$yMax);
    echo "<h4> S(a,b,c): $funcion </h4>";
    echo "<br>";
   

    $tol=1e-2;
    $resultado=true;
    /*$gradiente;
    while($resultado){
        for ($i=0; $i <=$n ; $i++) { 
            $funcionN=$gradiente[$i];
            for ($j=0; $j <=$n ; $j++) { 
                $strJacobiana=$jacobiana[$i][$j];
                $matrixA[$i][$j]=derivada($x0[$j],$constantes[$j],$strJacobiana);
                   
                $funcionN=str_replace("$constantes[$j]","$x0[$j]",$funcionN);
               
                
            } 
            
            eval("\$b[$i]=$funcionN;");
           
        }

        printMatrix($matrixA,$b);
        

        for ($i=0; $i <= $n; $i++) {
            $resultado=false; 
           if(abs($b[$i])>$tol){
            $resultado=true;
            break;   
           }
        }

       
        $h=elimacionGaussiana($matrixA,$b);
       
        echo " <br> Correccion de valores de X <br>";
       // $x0=$h;
        for ($i=0; $i <= $n; $i++) { 
            $x0[$i]-=$h[$i];
            echo " $x0[$i] ";
        }
        echo " <br>";
        





    }*/

    //A. Llene la Tabla 1 a partir de los datos que obtenga en la Fig. 1:

    

   // Graficadora de Geogebra
   echo "
   <section id='grafica' class='margen'>
   <h3> Gráfica de la función</h3>
   <div id='ggb-element' style='margin: 0 auto'></div>
   
       <script>
           var ggbApp = new GGBApplet({
               'appName': 'graphing',
               'showZoomButtons':true,
               'height': 640,
              
               'appletOnLoad': function(api) { 
                
                ";
                $n=$n-1;
                $a=$x[0];
                for ($i=0; $i < $n; $i++) { 
                    $b=$x[$i+1];
                    echo "api.evalCommand('($x[$i], $y[$i])');";
                    echo "api.evalCommand('Function($funcionLineal[$i],$a,$b)');";
                    $a=$x[$i+1];
                }
                echo "api.evalCommand('($x[$n], $y[$n])');";
                $a=$x[0];
                echo "api.evalCommand('Function($funcionCuadratica,$a,$x[$n])');";
                echo "api.evalCommand('Function($funcionLagrangeana,$a,$x[$n])');";
                
    echo "       
        }}, true);
               window.addEventListener('load', function() {
                   ggbApp.inject('ggb-element');
               });
       </script>
   </section>
   ";
   

}


//Funcion que construira la funcion S(a,b,c)
function armarFuncionS($x,$y,$f,$n,$yMax){
    $funcionS='';
    for ($i=0; $i < $n; $i++) { 

        //Funcion [f(x_i)-(y_i/yMax)]**2
        $fx='('.$f.'-('.$y[$i].'/'.$yMax.'))**2';

        //Función reemplazo de x
        $fx=str_replace("x",$x[$i],$fx);

        //Contatenación de la función
        if($i==$n-1)
            $funcionS=$funcionS.$fx;
        else
            $funcionS=$funcionS.$fx.'+';
        
    }
    return $funcionS;
}

//Primera Derivada
function primeraDerivada($x,$val,$funcion){
    $dx = 0.1; 
    $tolerancia = 1e-7;
    $pendiente_L = pendienteUno($x,$dx,$val,$funcion);
    do {
        $pendiente_L_1 = $pendiente_L;
        $dx/=2;
        $pendiente_L = pendienteUno($x,$dx,$val,$funcion);
        
    } while (abs($pendiente_L-$pendiente_L_1)>$tolerancia);
    return $pendiente_L;
}

function pendienteUno($x,$dx,$val,$funcion){
    return (ejecutar($x+$dx,$val,$funcion)-ejecutar($x,$val,$funcion))/$dx;
}

//Segunda derivada


function derivadaDos($x,$val,$funcion){
    $dx = 0.1; 
    $tolerancia = 1e-7;
    $pendiente_L = pendienteDos($x,$dx,$val,$funcion);
    do {
        $pendiente_L_1 = $pendiente_L;
        $dx/=2;
        $pendiente_L = pendienteDos($x,$dx,$val,$funcion);
        
    } while (abs($pendiente_L-$pendiente_L_1)>$tolerancia);
    return $pendiente_L;
}

function pendienteDos($x,$dx,$val,$funcion){
    return (ejecutar($x+$dx,$val,$funcion)-2*ejecutar($x,$val,$funcion)+ejecutar($x-$dx,$val,$funcion))/($dx**2);
}


//Eliminación Gaussiana
function elimacionGaussiana($r,$s){
    $n=count($s);
    for($k=0;$k<$n-1;$k++){
        for($i=$k+1;$i<$n;$i++){
            if($r[$k][$k] != 0){
            $m=round($r[$i][$k]/$r[$k][$k],3);
            for($j=$k+1;$j<$n;$j++){
               $r[$i][$j]=round($r[$i][$j]-$m*$r[$k][$j],3);
            }
            $r[$i][$k]=0;
            $s[$i]=round($s[$i]-$m*($s[$k]),3);
            }else
                return -1;
        }
    }
    for($i=$n-1;$i>=0;$i--){
        $suma=0;
        for($j=$i+1;$j<$n;$j++){
            $suma+=$t[$j]*$r[$i][$j];
        }
        $t[$i]=round(($s[$i]-$suma)/$r[$i][$i],3);
    } 
    return $t;
}


//Mostrar valores
function showResult($vector){
    echo "<div class='results'>";
        echo "<div class='flexbox'>";
            echo "<div class='box'>";
               
                echo "<table class='table table-bordered tabla'>";
                    echo "<tr>".printVector($vector,null)."</tr>";
                echo "</table>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}

//Impresion de vectores
function printVector($vec,$b){
    foreach($vec as $value){
        echo "<td>$value</td> ";
    }
    if($b){
        echo "<td bgcolor='green' color='white'>$b</td> ";
    }
}

//Impresion de matrices
function printMatrix($a,$b) {
    echo "<table class='table table-bordered'>";
    for ($l=0; $l < count($a); $l++) { 
        if($b){
            echo "<tr>".printVector($a[$l],$b[$l])."</tr>";
        }else{
            echo "<tr>".printVector($a[$l],$b)."</tr>";
        }
        
    }
    echo "</table>";
}







?>
</section>

<footer id="footer" class="bg-dark footer" >
       
	   <div class="footer-details">
		   <a href="https://github.com/Herziox" target="_blank">
		   <img src="https://i.imgur.com/rgpLnEz.png" class="img-fluid image-nav"  alt="Responsive image">
		   </a> 
		   
	   <p>
	   Desarrollado por       
	   </br>Sergio  Jiménez 
	   </br>
		 2020 Métodos Numéricos
	   </p>
	   </div>

	   <div class="footer-details">
	   <a href="https://github.com/Herziox/Cuadratura_Gaussiana">
	   <img src="https://i.imgur.com/3VAL0Cj.png" class="img-fluid "  alt="Responsive image">
	   </a>
	   </div>
   </footer>

 
</body>

</html> 