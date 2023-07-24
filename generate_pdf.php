<?php
require('fpdf.php');

// Obtener los datos de la bolsa desde LocalStorage
$bagData = json_decode($_POST['pokemonBag'], true);

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 10, 'Bolsa pokemon', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Courier', '', 12);

// Agregar los Pokémon a la lista en el PDF
foreach ($bagData as $pokemon) {
    $pdf->Cell(0, 10, $pokemon['name'], 0, 1);
    $pdf->Cell(0, 10, 'Tipo: ' . $pokemon['type'], 0, 1);
    $pdf->Image($pokemon['imageSrc'], 10, $pdf->GetY(), 25);
    $pdf->Ln(15);
}

$pdf->Output('D', 'pokemon_bag.pdf'); // Descargar el PDF con el nombre "pokemon_bag.pdf"
?>
