<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Pdf;
  
class PDFController extends Controller
{
    /**
     * Write code on Construct
     *
     * @return \Illuminate\Http\Response
     */
    public function preview()
    {
        return view('chart');
    }
  
    /**
     * Write code on Construct
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
{
    $render = view('chart')->render();
  
    $pdf = new Pdf();
    $pdf->addPage($render);
    $pdf->setOptions(['javascript-delay' => 5000]);
    $filePath = public_path('report.pdf');
    
    if (!$pdf->saveAs($filePath)) {
        return response()->json(['error' => $pdf->getError()], 500);
    }
   
    return response()->download($filePath);
}
}