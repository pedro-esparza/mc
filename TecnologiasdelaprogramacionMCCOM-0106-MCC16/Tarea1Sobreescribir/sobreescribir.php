<?php
/*********************************************************************************************

I.S.C. Pedro Esparza 9041301, Tecnologias de la programación MCCOM-0106 - MCC16, 02 Sept 2024

1. Guarda este archivo con el nombre que gustes ej filename.php.
2. Abre la terminal integrada en VSCode.
3. Navega al directorio donde se encuentra este archivo utilizando el comando 'cd'.
4. Ejecuta el comando 'php filename.php' para ver la salida.
5. Observa la magia

*********************************************************************************************/


// Clase base Animal
class Animal
{
    protected string $nombre;
    protected int $edad;

    public function __construct(string $nombre, int $edad)
    {
        if (!is_string($nombre) || empty(trim($nombre))) {
            throw new InvalidArgumentException("El nombre debe ser una cadena no vacía.");
        }
        if (!is_int($edad) || $edad < 0) {
            throw new InvalidArgumentException("La edad debe ser un entero no negativo.");
        }

        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    // Método que devuelve el sonido del animal y es "virtual" en el sentido de que puede ser sobrescrito.
    public function sonido(): string
    {
        return "Sonido de animal genérico";
    }
}

// Clase León extiende Animal.
class Leon extends Animal
{
    private bool $melena;

    public function __construct(string $nombre, int $edad, bool $melena)
    {
        parent::__construct($nombre, $edad);

        if (!is_bool($melena)) {
            throw new InvalidArgumentException("Melena debe ser un valor booleano.");
        }

        $this->melena = $melena;
    }

    // Método que devuelve el sonido del animal y es "virtual" en el sentido de que puede ser sobrescrito.
    public function sonido(): string
    {
        return "Rugido";
    }
}

// Clase Águila extiende Animal.
class Aguila extends Animal
{
    private float $alas;

    public function __construct(string $nombre, int $edad, float $alas)
    {
        parent::__construct($nombre, $edad);

        if (!is_float($alas) || $alas <= 0) {
            throw new InvalidArgumentException("La envergadura de las alas debe ser un número flotante positivo.");
        }

        $this->alas = $alas;
    }

    // Método que devuelve el sonido del animal y es "virtual" en el sentido de que puede ser sobrescrito.
    public function sonido(): string
    {
        return "Chillido";
    }
}

// Clase Tortuga extiende Animal.
class Lobo extends Animal
{
    private int $manada;

    public function __construct(string $nombre, int $edad, int $manada)
    {
        parent::__construct($nombre, $edad);

        if (!is_int($manada) || $manada <= 0) {
            throw new InvalidArgumentException("El tamaño de la manada debe ser un entero positivo.");
        }

        $this->manada = $manada;
    }

    // Método que devuelve el sonido del animal y es "virtual" en el sentido de que puede ser sobrescrito.
    public function sonido(): string
    {
        return "Aullido";
    }
}


// Instancias
$leon = new Leon('Simba', 8, true);
$aguila = new Aguila('Águila Real', 5, 2.5);
$lobo = new Lobo('Alpha', 5, 8);


// Ejecutar los metodos de sonido
echo $leon->sonido() . PHP_EOL;     // Salida: Rugido
echo $aguila->sonido() . PHP_EOL;   // Salida: Chillido
echo $lobo->sonido() . PHP_EOL;     // Salida: Aullido