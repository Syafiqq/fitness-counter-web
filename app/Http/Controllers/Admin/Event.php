<?php

namespace App\Http\Controllers\Admin;

use App\Firebase\DataMapper;
use App\Firebase\FirebaseConnection;
use App\Firebase\PopoMapper;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Event extends Controller
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getOverview($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.overview.admin_event_overview_{$this->theme}", compact('meta'));
    }

    public function getManagementRegistrar($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.management.registrar.admin_event_management_registrar_{$this->theme}", compact('meta'));
    }

    public function getManagementTester($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.management.tester.admin_event_management_tester_{$this->theme}", compact('meta'));
    }

    public function getEvaluationReport($event)
    {
        $meta = [
            'event' => $event
        ];

        return view("layout.admin.event.report.evaluation.admin_event_report_evaluation_{$this->theme}", compact('meta'));
    }

    public function getPublishEvaluation(FirebaseConnection $firebase, $event)
    {
        $jEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::event(null, null, $event)['events'])
            ->getValue() ?: [];

        $pEvent = $firebase
            ->getConnection()
            ->getDatabase()
            ->getReference(DataMapper::preset(null, $jEvent['preset_active'])['presets'])
            ->getValue() ?: [];

        try
        {
            /** @var \Carbon\Carbon $now */
            $now = \Carbon\Carbon::now();
            /** @var Spreadsheet $spreadsheet */
            $spreadsheet = new Spreadsheet();
            $filename    = "Daftar Nilai Ujian Keterampilan Bidang Olahraga SBMPTN {$now->year}";

            // Set document properties
            $spreadsheet->getProperties()->setCreator('Universitas Negeri Malang')
                ->setLastModifiedBy('Universitas Negeri Malang')
                ->setTitle('Daftar Nilai Ujian Keterampilan')
                ->setSubject('Bidang Olahraga')
                ->setDescription($filename)
                ->setKeywords('SBMPTN')
                ->setCategory('Ujian Keterampilan');

            // Set Active Sheet
            $spreadsheet->setActiveSheetIndex(0);

            $spreadsheet->getActiveSheet()->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $spreadsheet->getActiveSheet()->getPageMargins()
                ->setTop(0.492126)
                ->setBottom(0.492126)
                ->setLeft(0.492126)
                ->setRight(0.492126)
                ->setHeader(0.19685)
                ->setFooter(0.19685);

            //Column Width
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(16);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(36);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(7);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(13);


            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(base_path('/public_html/img/logo-sbmptn.png'));
            $drawing->setResizeProportional(true);
            $drawing->setWidthAndHeight(150, 120);
            $drawing->setCoordinates('C1');
            $drawing->setOffsetX(225);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());

            //Title
            $spreadsheet->getActiveSheet()->mergeCells('A8:G8');
            $spreadsheet->getActiveSheet()->getStyle('A8')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A8')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A8', 'DAFTAR NILAI UJI KETERAMPILAN');

            //Subtitle
            $spreadsheet->getActiveSheet()->mergeCells('A9:G9');
            $spreadsheet->getActiveSheet()->getStyle('A9')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A9')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A9', 'BIDANG : SENI RUPA / SENI TARI / SENI MUSIK / OLAHRAGA');

            //Origin
            $spreadsheet->getActiveSheet()->mergeCells('A10:G10');
            $spreadsheet->getActiveSheet()->getStyle('A10')->getFont()
                ->setSize(14)
                ->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A10')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->getActiveSheet()->setCellValue('A10', 'UNIVERSITAS :.........................................');

            //Table Header
            foreach ([
                         ['c' => 'A', 'v' => 'NO URUT'],
                         ['c' => 'B', 'v' => 'NOMOR PESERTA'],
                         ['c' => 'C', 'v' => 'NAMA PESERTA'],
                         ['c' => 'D', 'v' => 'NILAI'],
                         ['c' => 'E', 'v' => 'TIDAK HADIR'],
                         ['c' => 'F', 'v' => 'WAJAH TIDAK SESUAI FOTO PADA ABHP'],
                         ['c' => 'G', 'v' => 'KETERANGAN'],
                     ] as $c)
            {
                $spreadsheet->getActiveSheet()->getStyle("{$c['c']}12")->getFont()
                    ->setSize(11)
                    ->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle("{$c['c']}12")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $spreadsheet->getActiveSheet()->setCellValue("{$c['c']}12", $c['v']);
            }

            $leftBlock = [
                'font' => [
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ];

            $centerBlock                            = $leftBlock;
            $centerBlock['alignment']['horizontal'] = Alignment::HORIZONTAL_CENTER;

            $queues = [];
            foreach ($pEvent['queues'] as &$dv)
            {
                foreach ($dv as &$qv)
                {
                    if ($qv != null)
                    {
                        $queues[$qv['participant']['no']] = &$qv;
                    }
                }
            }
            $participantCount = count($jEvent['participant']) + 11;
            $spreadsheet->getActiveSheet()->getStyle("D13:G$participantCount")->applyFromArray($centerBlock);
            $spreadsheet->getActiveSheet()->getStyle("A13:C$participantCount")->applyFromArray($leftBlock);
            $spreadsheet->getActiveSheet()->getStyle("B13:B$participantCount")->applyFromArray($centerBlock);
            $spreadsheet->getActiveSheet()->getStyle("A12:G$participantCount")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $activeSheet = $spreadsheet->getActiveSheet();
            $startCount  = 12;
            foreach ($jEvent['participant'] as $pk => &$pv)
            {
                if ($pv != null)
                {
                    ++$startCount;
                    $activeSheet->setCellValue("A$startCount", $pk);
                    $activeSheet->setCellValue("B$startCount", $pv['no']);
                    $activeSheet->setCellValue("C$startCount", $pv['name']);
                    $queue = &$queues[$pv['no']];
                    if ($queue == null)
                    {
                        $activeSheet->setCellValue("E$startCount", 1);
                        $activeSheet->setCellValue("D$startCount", 0);
                    }
                    else
                    {
                        $result = 0;
                        foreach ($queue as &$kv)
                        {
                            if (key_exists('result', $kv))
                            {
                                $result += intval($kv['result']);
                            }
                        }
                        $activeSheet->setCellValue("D$startCount", $result);
                        if (key_exists('participant', $queue) && key_exists('same', $queue['participant']) && $queue['participant']['same'] == 0)
                        {
                            $activeSheet->setCellValue("F$startCount", 1);
                        }
                    }
                }
            }

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$filename.xlsx\"");
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header("Last-Modified: {$now->toRfc7231String()}"); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            ob_start();
            $writer->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();

            return response()->json(PopoMapper::jsonResponse(200, 'success', ['download' => ['content' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $filename . '.xlsx']]), 200);
        }
        catch (\Exception $e)
        {
            \Illuminate\Support\Facades\Log::debug($e);
        }

        return response()->json(PopoMapper::jsonResponse(500, 'failed', []), 500);

    }
}
