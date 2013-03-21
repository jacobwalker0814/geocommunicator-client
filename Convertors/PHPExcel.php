<?php

namespace GeoServices\Services;

class Converter
{
    public function convert($file, $startingRow=1)
    {
        $file = '/tmp/foobar.xls'; // TODO this is for testing
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $max = $sheet->getHighestRow();
        for($i = $startingRow; $i <= $max; ++$i) {
            $lat     = $sheet->getCellByColumnAndRow(0, $i)->getValue();
            $long    = $sheet->getCellByColumnAndRow(1, $i)->getValue();
            $request = Request::getInstance($lat, $long);

            if(false === $request->getError()) {
                $sheet->setCellValueByColumnAndRow(2,  $i, $request->getState());
                $sheet->setCellValueByColumnAndRow(3,  $i, $request->getPrincipalMeridian());
                $sheet->setCellValueByColumnAndRow(4,  $i, $request->getTownshipNumber());
                $sheet->setCellValueByColumnAndRow(5,  $i, $request->getTownshipFraction());
                $sheet->setCellValueByColumnAndRow(6,  $i, $request->getTownshipDirection());
                $sheet->setCellValueByColumnAndRow(7,  $i, $request->getRangeNumber());
                $sheet->setCellValueByColumnAndRow(8,  $i, $request->getRangeFraction());
                $sheet->setCellValueByColumnAndRow(9,  $i, $request->getRangeDireciton());
                $sheet->setCellValueByColumnAndRow(10, $i, $request->getSection());
                $sheet->setCellValueByColumnAndRow(11, $i, $request->getTownshipDuplicate());
            } else {
                $sheet->setCellValueByColumnAndRow(3, $i, $request->getError());
            }
        }

        // TODO generic writer based on file type
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($file);

        return $file;
    }
}
