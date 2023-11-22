<?php
namespace App\Http\Controllers;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use App\Http\Controllers\Controller;


class ExportsController extends Controller
{
    function exportarCSV(){
        
        
        // Crear un nuevo escritor (writer)
        $writer = WriterEntityFactory::createXLSXWriter();
        
        // Establecer la ruta del archivo
        $filePath = 'prueba.xlsx';
        
        $writer->openToBrowser($filePath);

        
        // Agregar encabezados
        $headerRow = WriterEntityFactory::createRowFromArray(['Nombre', 'Edad', 'Correo']);
        $writer->addRow($headerRow);
        
        // Agregar datos
        $dataRows = [
            WriterEntityFactory::createRowFromArray(['Juan', 25, 'juan@example.com']),
            WriterEntityFactory::createRowFromArray(['María', 30, 'maria@example.com']),
            // Puedes seguir agregando más filas según sea necesario
        ];
        
        foreach ($dataRows as $dataRow) {
            $writer->addRow($dataRow);
        }
        
        // Cerrar el escritor (writer)
        $writer->close();
        

   
        
        // Salir del script después de la descarga
        exit;
    }
}
