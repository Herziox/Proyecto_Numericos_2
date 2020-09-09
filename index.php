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
            <h1> Proyecto de Segundp Bimestre (Version alpha 2.0)</h1>
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

    //Incognitas
    $incognitas = ['a','b','c'];
        // a ancho de la campana
        // b desplazamientod de la funcion
        // c altura de la funcion
    //CONSTANTES
    $yMax=1087.7814;
    $tol=1e-2;
    $m=count($incognitas);

    //VARIABLES
    $resultado=true;

    // S(a,b,c)
    $vectorF;

    //Vector inicial de los valores [a,b,c]
    $vectorZ=[1e-6,850,0.7];

    //Jacobina 
    $jacobiana;

    //Jacobiana Transpuesta
    $jacobianaT;

    //Contador de iteraciones
    $cont=0;

    //Ingreso de puntos en el plano cartesiano
    if($_POST['intro']=="texto"){
        //Ingreso a traves de campo
        $puntos=$_POST['puntos'];
        $valores = explode(";",$puntos);
        $n=count($valores);
        for ($i=0; $i < $n; $i++) { 
            $value=explode(",",$valores[$i]);
            $x[$i]=$value[0];
            $y[$i]=$value[1];
            
        }
    }else{
        //Ingreso a traves de archivo txt
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
            
           
        }
    }
    
    $values[0]=$x;
    $values[1]=$y;

    //Funcion Principal
    $funcion = '(1/(1+a*(x-x0)**2))+(c/(1+a*(x-x0-b)**2))';
    echo "Función: $funcion <br>";

    //Reemplazo del x0
    $funcion = str_replace('x0','4000',$funcion);
    echo "Función: $funcion <br>";

    $yNormal=array();


    while($resultado && $cont<30){
      
        //Armar Vecor F,  Jacobiana y Jacobiana Traspuesta
        for ($i=0; $i < $n; $i++) { 
            //Funcion Normalizada
            $yNormal[$i]=eval("return $y[$i]/$yMax;");  

            //Funcion para obtener la Jacobiana
            $funcionAux=$funcion;
            $funcionAux = str_replace('x',$x[$i],$funcionAux);
            $funcionAux = $funcionAux.'-('.$yNormal[$i].')';

            //Funcion para obtener el VectorF
            $funcionF = $funcionAux;
            //echo "<br>$funcionAux iter $i<br>";
           
            for ($j=0; $j < $m; $j++) {
                $funcionJ = $funcionAux;      
                for ($k=0; $k < $m; $k++) { 
                    $valor='('.$vectorZ[$k].')';
                    if($j!=$k){         
                        $funcionJ = str_replace($incognitas[$k], $valor,$funcionJ);
                    }
                    //Reemplazo de incognitas en la funcionF para obtener el vectorF
                    $funcionF = str_replace($incognitas[$k], $valor,$funcionF);
                }

                //Jacobiana 
                // echo " $funcionJ, $vectorZ[$j], $incognitas[$j], iterJ= $j <br>";
                $jacobiana[$i][$j]=primeraDerivada($vectorZ[$j],$incognitas[$j],$funcionJ);

                //Jacobiana Traspuesta
                $jacobianaT[$j][$i]=$jacobiana[$i][$j];
            } 
            //echo "<br>$funcionF <br>";

            //Armar el Vector F
             $vectorF[$i][0]= eval("return $funcionF;");
        }
        
        //Producto de Jacobiana traspuesta por la jacobiana
        $a=productoDeMatrices($jacobianaT,$jacobiana);

        // Multiplicacion de -1 por la matriz Jacobiana traspuesta
        $jacobianaT_N=prpductoMatrizEscalar($jacobianaT,-1);   

        //Multiplicacion de la Jacobiana Traspuesta Negativa por el vectorF
        $vectorAux=productoDeMatrices($jacobianaT_N,$vectorF);

        //Variable auxiliar para la impresión
        $jtxf=$vectorAux;

        //Normalización de matriz a vector
        $b=obtenerVector($vectorAux);

         //Variable auxiliar para los anteriores valores del vectorZ
        $vectorAux=$vectorZ;

        //Aplicación de la elimancion gaussiana para los nuevos valores del vectorZ
        $vectorZ=eliminacionGaussiana($a,$b);

 /*    
        //Impresion de resultados

           echo "<div class='flexbox'>";
                echo "<div class='box'>";
                    echo "</br> <h2>Jacobiana Transpuesta</h2></br>";
                    printMatrix($jacobianaT,null);
                echo "</div>";
        
                echo "<div class='box'>";
                    echo "</br><h2> Jacobiana</h2> </br>";
                    printMatrix($jacobiana,null);
                echo "</div>";
              
                echo "<div class='box'>";
                    echo "</br> <h1>=</h1> </br>"; 
                echo "</div>";
                echo "<div class='box'>";
                    echo "</br><h2> Jacobiana Transpuesta</h2></br>";
                    printMatrix($jacobianaT_N,null);
                echo "</div>";
                echo "<div class='box'>";
                    echo "</br> <h2>Vector F</h2></br>";
                    printMatrix($vectorF,null);
                echo "</div>";
        echo "</div>";

 
        echo "<div class='flexbox'>";

                echo "<div class='box'>";
                    echo "</br><h2> Matriz A y vector Delta Z</h2></br>";
                    printMatrix($a,$incognitas);
                echo "</div>";

              
                echo "<div class='box'>";
                    echo "</br> <h1>=</h1> </br>"; 
                echo "</div>";

                echo "<div class='box'>";
                    echo "</br><h2> Jacobiana Transpuesta</h2></br>";
                    printMatrix($jtxf,null);
                echo "</div>";

        echo "</div>";
       */ 
    

        for ($i=0; $i < $m; $i++) { 
            $val = $vectorAux[$i]-$vectorZ[$i];
            $resultado=false;
            if(abs($val)>$tol){
                $cont++;
                $resultado=true;
                break;
            }
        }

        for ($i=0; $i < $m; $i++) { 
            $vectorZ[$i]+=$vectorAux[$i];
        }
        
       /*
        echo "<div class='flexbox'>";
        echo "<div class='box'>";
            echo "</br> <h2>Vector Z (iteración $cont)</h2></br>";
            showResult($vectorAux);
        echo "</div>";

        echo "<div class='box'>";
            echo "</br> <h2>+</h2></br>";
            showResult($vectorZ);
        echo "</div>";

        echo "<div class='box'>";
            echo "</br> <h2> = Vector Z (iteración $cont)</h2></br>";
            showResult($vectorZ);
        echo "</div>";
     echo "</div>";
        */
   }

    echo "<div class='flexbox'>";
        echo "<div class='box'>";
            echo "</br> <h2> Vector Z (iteración $cont)</h2></br>";
            showResult($vectorZ);
        echo "</div>";
    echo "</div>";

   if($cont>=30){
    echo "<div class='error'><br> NO se encontro los valores de ajuste <br></div>";
   }



    
    $tabla1=array();
    $tabla2=array();
    $l=5;
    $tabla1[0]=array('n','canal','conteo','conteo normalizado');
    $tabla2[0]=array('n','canal','conteo normalizado','curva','diferencia');
    $funcionAux=$funcion
    for ($i=0; $i < $m; $i++) { 
        $funcion = str_replace($incognitas[$i],'('.$vectorZ[$i].')',$funcion);
    }
    //echo "$funcion <br>";
    for ($i=0; $i < $n; $i++) { 

        //A. Llene la Tabla 1 a partir de los datos que obtenga en la Fig. 1
        $tabla1[$i+1]=array($i,$x[$i],$y[$i],$yNormal[$i]);

         //B. Llene la Tabla 2 a partir del calculo compuacional
        $yCurva = $funcion;
        $yCurva = str_replace('x','('.$x[$i].')',$yCurva);
        //echo "$yCurva <br>";
        $yCurva = eval("return $yCurva;");
        $diferencia = eval("return abs($y[$i]-$yCurva);");
        $tabla2[$i+1]=array($i,$x[$i],$yNormal[$i],$yCurva,$diferencia);
    }
    echo "<h2>Tabla 1</h2>";
    printMatrix($tabla1,null);
    echo "<h2>Tabla 2</h2>";
    printMatrix($tabla2,null);
    

   

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

                
    echo "       
        }}, true);
               window.addEventListener('load', function() {
                   ggbApp.inject('ggb-element');
               });
       </script>
   </section>
   ";
   

}


//Función que transforma una matriz nx1 en un vector de rango n (posiblemente modificable) 
function obtenerVector($b){
    $matB=array();
    $n=count($b);
    for ($i=0; $i < $n; $i++) { 
        for ($j=0; $j < count($b[0]); $j++) {
            $matB[$i]=$b[$i][$j];
        }
    }
    return $matB;
}

//Función de producto de matrices 
function productoDeMatrices($matA,$matB){
    $matF = array();
    $n = count($matA);
    $m = count($matB[0]);
    $l = count($matA[0]);
   // echo "$n , $m  , $l <br>";
    for ($i=0; $i < $n; $i++) { 
        for ($j=0; $j < $m; $j++) {
            $sum=0;
           // echo "Valor mat[$i][$j] <br>";
            for ($k=0; $k < $l; $k++) { 
                $sum+=$matA[$i][$k]*$matB[$k][$j];
                //echo "$i , $j  , $k <br>";
            }
           // echo "la suma es $sum<br>";
            $matF[$i][$j]=$sum;
        }
    }
    
    return $matF;
}



// Funcion matriz x escalar
function prpductoMatrizEscalar($matriz,$escalar) {
    $matxesc = array();
    
        foreach ($matriz as $row_key => $row) {
            foreach ($row as $column_key => $element) {
                $matxesc[$row_key][$column_key] = $escalar*$element;
            }
        }
    return $matxesc;    
} 
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
    return (ejecutar($x+$dx,$val,$funcion)-ejecutar($x-$dx,$val,$funcion))/(2*$dx);
}

//Eliminación Gaussiana
function eliminacionGaussiana($r,$s){
    $n=count($r);
    for($k=0;$k<$n-1;$k++){
        for($i=$k+1;$i<$n;$i++){
            if($r[$k][$k] != 0){
            $m=$r[$i][$k]/$r[$k][$k];
            for($j=$k+1;$j<$n;$j++){
               $r[$i][$j]=$r[$i][$j]-$m*$r[$k][$j];
            }
            $r[$i][$k]=0;
            $s[$i]=$s[$i]-$m*($s[$k]);
            }else
                return -1;//No tiene solucion
        }
    }
    return soluciones($r,$s,$n);
}

function soluciones($r,$s,$n){
    for($i=$n-1;$i>=0;$i--){
        $suma=0;
        for($j=$i+1;$j<$n;$j++){
            $suma+=$t[$j]*$r[$i][$j];
        }
        $t[$i]=round(($s[$i]-$suma)/$r[$i][$i],3);
    } 
    return $t;
}

//Funcion para ejecutar funciones
function ejecutar($x,$val,$funcion){
    $res=0;
    $funcion=str_replace("$val",'('.$x.')', $funcion);
    //echo "<br> Funcion Evaluada $funcion <br>";   
    try {
       eval("\$res=$funcion;");
    } catch (Throwable $t) {
        $res = null;
        echo "<div class='error'><br> error en la evaluacion <br></div>";
        echo "Funcion evaluada: $funcion";
    }

    return $res;
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