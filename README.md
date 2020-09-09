# Proyecto_Numericos_2
Ajuste de curvas

Un investigador del Laboratorio de Ensayos Físicos de la Escuela Politécnica Nacional obtuvo los siguientes
datos experimentales presentados en la Fig. 1. Estos datos fueron obtenidos con un difractómetro de rayos
X. En el eje horizontal está representado el número del canal en el detector de difracción (canales con
numeración de 0 a 8000), y en el eje vertical está representado en conteo para cada canal (valores de 0 a
1200). Este investigador sabe que el pulso observado en la Fig. 1 es en realidad compuesto por la
combinación de dos pulsos menores, también representados en la figura.
 
 Figura_1.png
 
 Dado que 𝑥 es el canal para el cual se obtiene el mayor conteo, los datos experimentales representados en
la Fig. 1 pueden ser ajustados por la siguiente curva
 
Ecuacio_1.png


Para poder determinar los valores de las incógnitas 𝛼, 𝛿 y 𝑐, ejecute los siguientes pasos:

a) Llene la Tabla 1 a partir de los datos que obtenga en la Fig. 1:

b) Con el objetivo de minimizar el funcional dado por la suma de los residuos cuadrados 
 
Ecuacio_2.png

donde los elementos del vector 𝐹⃗ son dados por 𝐹 = 𝑦(𝑥
) − 𝑦, 𝑖 = 1,2, … , 𝑁, tres ecuaciones no
lineales se obtienen al escribirse 

tabla_1.png

Muestre (o sea, deduzca las ecuaciones) que un procedimiento iterativo puede ser construido para la
solución del sistema de ecuaciones no lineales, donde a partir de una estimativa inicial, nuevas
estimativas son obtenidas con 𝑍⃗ a partir de las cuales puede ser determinado el vector de incógnitas 𝑍⃗ = {𝛼, 𝛿, 𝑐}
donde el vector de correcciones 
∆𝑍⃗= 𝑍⃗+ ∆𝑍⃗
es obtenido a partir de la ecuación
(𝐽)𝐽∆𝑍⃗= −(𝐽)𝐹⃗
y 𝐽 es la matriz Jacobiana.
c) Escriba una rutina computacional para la determinación del vector de incógnitas 𝑍⃗. Haga una tabla
mostrando los valores del vector 𝑍⃗
 para cada iteración 𝑛. Describa el criterio de parada utilizado.
d) Compare los resultados obtenidos experimentalmente (𝑥 , 𝑦), 𝑖 = 1,2, … , 𝑁, con aquellos calculados
usando la curva, i.e. 𝑥 , 𝑦(𝑥), 𝑖 = 1,2, … , 𝑁 , empleando la Tabla 2. 
