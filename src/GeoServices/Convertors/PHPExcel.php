<?php

namespace GeoServices\Convertors;

use GeoServices\Services\Request as Request;

class PHPExcel
{
    public function convert($file, $startingRow=1)
    {
        $objPHPExcel = $this->getPHPExcel($file);

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
                $sheet->setCellValueByColumnAndRow(9,  $i, $request->getRangeDirection());
                $sheet->setCellValueByColumnAndRow(10, $i, $request->getSection());
                $sheet->setCellValueByColumnAndRow(11, $i, $request->getTownshipDuplicate());
            } else {
                $sheet->setCellValueByColumnAndRow(3, $i, $request->getError());
            }
        }

        $this->savePHPExcel($objPHPExcel, $file);
    }

    public function addTitles($file, $row)
    {
        $objPHPExcel = $this->getPHPExcel($file);

        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $sheet->setCellValueByColumnAndRow(2,  $row, "State");
        $sheet->setCellValueByColumnAndRow(3,  $row, "Principal Meridian");
        $sheet->setCellValueByColumnAndRow(4,  $row, "Township Number");
        $sheet->setCellValueByColumnAndRow(5,  $row, "Township Fraction");
        $sheet->setCellValueByColumnAndRow(6,  $row, "Township Direction");
        $sheet->setCellValueByColumnAndRow(7,  $row, "Range Number");
        $sheet->setCellValueByColumnAndRow(8,  $row, "Range Fraction");
        $sheet->setCellValueByColumnAndRow(9,  $row, "Range Direction");
        $sheet->setCellValueByColumnAndRow(10, $row, "Section");
        $sheet->setCellValueByColumnAndRow(11, $row, "Township Duplicate");

        $this->savePHPExcel($objPHPExcel, $file);
    }

    protected function getPHPExcel($file)
    {
        return \PHPExcel_IOFactory::load($file);
    }

    protected function savePHPExcel($objPHPExcel, $file)
    {
        // TODO generic writer based on file type
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        return $objWriter->save($file);
    }
}
